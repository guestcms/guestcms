<?php

namespace Guestcms\Stripe\Http\Controllers;

use Guestcms\Base\Facades\BaseHelper;
use Guestcms\Base\Http\Controllers\BaseController;
use Guestcms\Base\Http\Responses\BaseHttpResponse;
use Guestcms\Payment\Enums\PaymentStatusEnum;
use Guestcms\Payment\Models\Payment;
use Guestcms\Payment\Supports\PaymentHelper;
use Guestcms\Stripe\Http\Requests\StripePaymentCallbackRequest;
use Guestcms\Stripe\Services\Gateways\StripePaymentService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Stripe\Checkout\Session;
use Stripe\Exception\SignatureVerificationException;
use Stripe\PaymentIntent;
use Stripe\Webhook;

class StripeController extends BaseController
{
    public function webhook(Request $request)
    {
        $webhookSecret = get_payment_setting('webhook_secret', 'stripe');
        $signature = $request->server('HTTP_STRIPE_SIGNATURE');
        $content = $request->getContent();

        if (! $webhookSecret || ! $signature || ! $content) {
            return response()->noContent();
        }

        try {
            do_action('payment_before_making_api_request', STRIPE_PAYMENT_METHOD_NAME, (array) $content);

            $event = Webhook::constructEvent(
                $content,
                $signature,
                $webhookSecret
            );

            do_action('payment_after_api_response', STRIPE_PAYMENT_METHOD_NAME, (array) $content, $event->toArray());

            if ($event->type == 'payment_intent.succeeded') {
                /**
                 * @var PaymentIntent $paymentIntent
                 */
                $paymentIntent = $event->data->object; // @phpstan-ignore-line

                $payment = Payment::query()
                    ->where('charge_id', $paymentIntent->id)
                    ->first();

                if ($payment) {
                    $payment->status = PaymentStatusEnum::COMPLETED;
                    $payment->save();

                    do_action(PAYMENT_ACTION_PAYMENT_PROCESSED, [
                        'charge_id' => $payment->charge_id,
                        'order_id' => $payment->order_id,
                    ]);
                }
            }
        } catch (SignatureVerificationException $e) {
            BaseHelper::logError($e);
        }

        return response()->noContent();
    }

    public function success(
        StripePaymentCallbackRequest $request,
        StripePaymentService $stripePaymentService,
        BaseHttpResponse $response
    ) {
        try {
            $stripePaymentService->setClient();

            $sessionId = $request->input('session_id');

            do_action('payment_before_making_api_request', STRIPE_PAYMENT_METHOD_NAME, ['id' => $sessionId]);

            $session = Session::retrieve($sessionId);

            do_action('payment_after_api_response', STRIPE_PAYMENT_METHOD_NAME, ['id' => $sessionId], $session->toArray());

            if ($session->payment_status == 'paid') {
                $metadata = $session->metadata->toArray();

                $orderIds = json_decode($metadata['order_id'], true);

                $charge = PaymentIntent::retrieve($session->payment_intent);

                if (! $charge->latest_charge) {
                    return $response
                        ->setError()
                        ->setNextUrl(PaymentHelper::getCancelURL())
                        ->setMessage(__('No payment charge. Please try again!'));
                }

                $chargeId = $charge->latest_charge;

                do_action(PAYMENT_ACTION_PAYMENT_PROCESSED, [
                    'amount' => $metadata['amount'],
                    'currency' => strtoupper($session->currency),
                    'charge_id' => $chargeId,
                    'order_id' => $orderIds,
                    'customer_id' => Arr::get($metadata, 'customer_id'),
                    'customer_type' => Arr::get($metadata, 'customer_type'),
                    'payment_channel' => STRIPE_PAYMENT_METHOD_NAME,
                    'status' => PaymentStatusEnum::COMPLETED,
                ]);

                return $response
                    ->setNextUrl(PaymentHelper::getRedirectURL() . '?charge_id=' . $chargeId)
                    ->setMessage(__('Checkout successfully!'));
            }

            return $response
                ->setError()
                ->setNextUrl(PaymentHelper::getCancelURL())
                ->setMessage(__('Payment failed!'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setNextUrl(PaymentHelper::getCancelURL())
                ->withInput()
                ->setMessage($exception->getMessage() ?: __('Payment failed!'));
        }
    }

    public function error(BaseHttpResponse $response)
    {
        return $response
            ->setError()
            ->setNextUrl(PaymentHelper::getCancelURL())
            ->withInput()
            ->setMessage(__('Payment failed!'));
    }
}
