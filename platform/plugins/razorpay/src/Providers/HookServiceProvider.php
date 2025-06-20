<?php

namespace Guestcms\Razorpay\Providers;

use Guestcms\Base\Facades\BaseHelper;
use Guestcms\Base\Facades\Html;
use Guestcms\Media\Facades\RvMedia;
use Guestcms\Payment\Enums\PaymentMethodEnum;
use Guestcms\Payment\Enums\PaymentStatusEnum;
use Guestcms\Payment\Facades\PaymentMethods;
use Guestcms\Razorpay\Forms\RazorpayPaymentMethodForm;
use Guestcms\Razorpay\Services\Gateways\RazorpayPaymentService;
use Guestcms\Theme\Facades\Theme;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

class HookServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        add_filter(PAYMENT_FILTER_ADDITIONAL_PAYMENT_METHODS, [$this, 'registerRazorpayMethod'], 11, 2);
        add_filter(PAYMENT_FILTER_AFTER_POST_CHECKOUT, [$this, 'checkoutWithRazorpay'], 11, 2);

        add_filter(PAYMENT_METHODS_SETTINGS_PAGE, [$this, 'addPaymentSettings'], 93);

        add_filter(BASE_FILTER_ENUM_ARRAY, function ($values, $class) {
            if ($class == PaymentMethodEnum::class) {
                $values['RAZORPAY'] = RAZORPAY_PAYMENT_METHOD_NAME;
            }

            return $values;
        }, 20, 2);

        add_filter(BASE_FILTER_ENUM_LABEL, function ($value, $class) {
            if ($class == PaymentMethodEnum::class && $value == RAZORPAY_PAYMENT_METHOD_NAME) {
                $value = 'Razorpay';
            }

            return $value;
        }, 20, 2);

        add_filter(BASE_FILTER_ENUM_HTML, function ($value, $class) {
            if ($class == PaymentMethodEnum::class && $value == RAZORPAY_PAYMENT_METHOD_NAME) {
                $value = Html::tag(
                    'span',
                    PaymentMethodEnum::getLabel($value),
                    ['class' => 'label-success status-label']
                )
                    ->toHtml();
            }

            return $value;
        }, 20, 2);

        add_filter(PAYMENT_FILTER_GET_SERVICE_CLASS, function ($data, $value) {
            if ($value == RAZORPAY_PAYMENT_METHOD_NAME) {
                $data = RazorpayPaymentService::class;
            }

            return $data;
        }, 20, 2);

        add_filter(PAYMENT_FILTER_PAYMENT_INFO_DETAIL, function ($data, $payment) {
            if ($payment->payment_channel == RAZORPAY_PAYMENT_METHOD_NAME) {
                $paymentService = new RazorpayPaymentService();
                $paymentDetail = $paymentService->getPaymentDetails($payment->charge_id);

                if ($paymentDetail) {
                    $data = view('plugins/razorpay::detail', ['payment' => $paymentDetail, 'paymentModel' => $payment])->render();
                }
            }

            return $data;
        }, 20, 2);

        add_filter(PAYMENT_FILTER_GET_REFUND_DETAIL, function ($data, $payment, $refundId) {
            if ($payment->payment_channel == RAZORPAY_PAYMENT_METHOD_NAME) {
                $refundDetail = (new RazorpayPaymentService())->getRefundDetails($refundId);
                if (! Arr::get($refundDetail, 'error')) {
                    $refunds = Arr::get($payment->metadata, 'refunds', []);
                    $refund = collect($refunds)->firstWhere('id', $refundId);
                    $refund = array_merge((array) $refund, Arr::get($refundDetail, 'data', []));

                    return array_merge($refundDetail, [
                        'view' => view('plugins/razorpay::refund-detail', ['refund' => $refund, 'paymentModel' => $payment])->render(),
                    ]);
                }

                return $refundDetail;
            }

            return $data;
        }, 20, 3);
    }

    public function addPaymentSettings(?string $settings): string
    {
        return $settings . RazorpayPaymentMethodForm::create()->renderForm();
    }

    public function registerRazorpayMethod(?string $html, array $data): string
    {
        $apiKey = get_payment_setting('key', RAZORPAY_PAYMENT_METHOD_NAME);
        $apiSecret = get_payment_setting('secret', RAZORPAY_PAYMENT_METHOD_NAME);

        if (! $apiKey || ! $apiSecret) {
            return $html;
        }

        $data['errorMessage'] = null;
        $data['orderId'] = null;

        if (get_payment_setting(
            'payment_type',
            RAZORPAY_PAYMENT_METHOD_NAME,
            'hosted_checkout',
        ) == 'website_embedded') {
            try {
                $api = new Api($apiKey, $apiSecret);

                $receiptId = $data['checkout_token'] ?? Str::random(20);

                $amount = $data['amount'] * 100;

                $requestData = [
                    'receipt' => $receiptId,
                    'amount' => (int) round($amount),
                    'currency' => $data['currency'],
                ];

                do_action('payment_before_making_api_request', RAZORPAY_PAYMENT_METHOD_NAME, $requestData);

                // @phpstan-ignore-next-line
                $order = $api->order->create($requestData);

                do_action('payment_after_api_response', RAZORPAY_PAYMENT_METHOD_NAME, $requestData, $order->toArray());

                $data['orderId'] = $order['id'];
            } catch (Exception $exception) {
                $data['errorMessage'] = $exception->getMessage();
            }
        }

        PaymentMethods::method(RAZORPAY_PAYMENT_METHOD_NAME, [
            'html' => view('plugins/razorpay::methods', $data)->render(),
        ]);

        return $html;
    }

    public function checkoutWithRazorpay(array $data, Request $request): array
    {
        if ($data['type'] !== RAZORPAY_PAYMENT_METHOD_NAME) {
            return $data;
        }

        $paymentData = apply_filters(PAYMENT_FILTER_PAYMENT_DATA, [], $request);

        $data['charge_id'] = $request->input('razorpay_payment_id');

        if (! $data['charge_id']) {
            $data['error'] = true;
            $data['message'] = __('Payment failed!');
        }

        $amount = (int) round($paymentData['amount'] * 100);

        $status = PaymentStatusEnum::PENDING;

        $apiKey = get_payment_setting('key', RAZORPAY_PAYMENT_METHOD_NAME);
        $apiSecret = get_payment_setting('secret', RAZORPAY_PAYMENT_METHOD_NAME);

        $api = new Api($apiKey, $apiSecret);

        if (get_payment_setting(
            'payment_type',
            RAZORPAY_PAYMENT_METHOD_NAME,
            'hosted_checkout',
        ) == 'hosted_checkout') {
            $receiptId = $data['checkout_token'] ?? Str::random(20);

            $requestData = [
                'receipt' => $receiptId,
                'amount' => $amount,
                'currency' => $data['currency'],
                'notes' => [
                    'order_id' => $paymentData['order_id'],
                    'order_token' => $paymentData['checkout_token'],
                    'customer_name' => $paymentData['address']['name'],
                    'customer_email' => $paymentData['address']['email'],
                    'customer_phone' => $paymentData['address']['phone'],
                ],
            ];

            do_action('payment_before_making_api_request', RAZORPAY_PAYMENT_METHOD_NAME, $requestData);

            // @phpstan-ignore-next-line
            $order = $api->order->create($requestData);

            do_action('payment_after_api_response', RAZORPAY_PAYMENT_METHOD_NAME, $requestData, $order->toArray());

            $paymentService = new RazorpayPaymentService();

            $paymentService->redirectToCheckoutPage([
                'key_id' => $apiKey,
                'amount' => $amount,
                'currency' => $data['currency'],
                'order_id' => $order['id'],
                'name' => Theme::getSiteTitle(),
                'description' => $paymentData['description'],
                'image' => Theme::getLogo() ? RvMedia::getImageUrl(Theme::getLogo()) : null,
                'callback_url' => route('payments.razorpay.callback', [
                    'token' => $paymentData['checkout_token'],
                    'customer_id' => $paymentData['customer_id'],
                    'customer_type' => $paymentData['customer_type'],
                    'order_id' => $paymentData['order_id'],
                ]),
                'cancel_url' => $paymentData['return_url'],
                'prefill[name]' => $paymentData['address']['name'],
                'prefill[email]' => $paymentData['address']['email'],
                'prefill[contact]' => $paymentData['address']['phone'],
                'notes[order_id]' => json_encode($paymentData['order_id']),
                'notes[order_token]' => $paymentData['checkout_token'],
                'notes[customer_name]' => $paymentData['address']['name'],
                'notes[customer_email]' => $paymentData['address']['email'],
                'notes[customer_phone]' => $paymentData['address']['phone'],
            ]);
        } else {
            try {
                $orderId = $request->input('razorpay_order_id');

                $signature = $request->input('razorpay_signature');

                if ($orderId && $signature) {
                    // @phpstan-ignore-next-line
                    $api->utility->verifyPaymentSignature([
                        'razorpay_signature' => $signature,
                        'razorpay_payment_id' => $data['charge_id'],
                        'razorpay_order_id' => $orderId,
                    ]);

                    do_action('payment_before_making_api_request', RAZORPAY_PAYMENT_METHOD_NAME, ['order_id' => $orderId]);

                    // @phpstan-ignore-next-line
                    $order = $api->order->fetch($orderId);

                    $order = $order->toArray();

                    do_action('payment_after_api_response', RAZORPAY_PAYMENT_METHOD_NAME, ['order_id' => $orderId], $order);

                    $amount = $order['amount_paid'] / 100;

                    $status = $order['status'] === 'paid' ? PaymentStatusEnum::COMPLETED : $status;
                }
            } catch (SignatureVerificationError $exception) {
                BaseHelper::logError($exception);

                $data['message'] = $exception->getMessage();
                $data['error'] = true;
            }

            do_action(PAYMENT_ACTION_PAYMENT_PROCESSED, [
                'amount' => $amount,
                'currency' => $paymentData['currency'],
                'charge_id' => $data['charge_id'],
                'payment_channel' => RAZORPAY_PAYMENT_METHOD_NAME,
                'status' => $status,
                'order_id' => $paymentData['order_id'],
                'customer_id' => $paymentData['customer_id'],
                'customer_type' => $paymentData['customer_type'],
            ]);
        }

        return $data;
    }
}
