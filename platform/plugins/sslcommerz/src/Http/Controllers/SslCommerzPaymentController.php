<?php

namespace Guestcms\SslCommerz\Http\Controllers;

use Guestcms\Base\Http\Controllers\BaseController;
use Guestcms\Base\Http\Responses\BaseHttpResponse;
use Guestcms\Payment\Enums\PaymentStatusEnum;
use Guestcms\Payment\Models\Payment;
use Guestcms\Payment\Supports\PaymentHelper;
use Guestcms\SslCommerz\Http\Requests\PaymentRequest;
use Guestcms\SslCommerz\Library\SslCommerz\SslCommerzNotification;

class SslCommerzPaymentController extends BaseController
{
    public function success(PaymentRequest $request, BaseHttpResponse $response)
    {
        $transactionId = $request->input('tran_id');
        $amount = $request->input('amount');
        $currency = $request->input('currency');
        $checkoutToken = $request->input('value_b');

        $sslc = new SslCommerzNotification();

        $validation = $sslc->orderValidate($request->input(), $transactionId, $amount, $currency);

        if (! $validation) {
            return $response
                ->setError()
                ->setNextUrl(PaymentHelper::getCancelURL($checkoutToken))
                ->setMessage(__('Payment failed!'));
        }

        $orderIds = explode(';', $request->input('value_a'));

        do_action(PAYMENT_ACTION_PAYMENT_PROCESSED, [
            'amount' => $request->input('amount'),
            'currency' => $currency,
            'charge_id' => $transactionId,
            'payment_channel' => SSLCOMMERZ_PAYMENT_METHOD_NAME,
            'status' => PaymentStatusEnum::COMPLETED,
            'customer_id' => $request->input('value_c'),
            'customer_type' => urldecode($request->input('value_d')),
            'payment_type' => 'direct',
            'order_id' => $orderIds,
        ]);

        return $response
            ->setNextUrl(PaymentHelper::getRedirectURL($checkoutToken))
            ->setMessage(__('Checkout successfully!'));
    }

    public function fail(PaymentRequest $request, BaseHttpResponse $response)
    {
        $checkoutToken = $request->input('value_b');

        return $response
            ->setError()
            ->setNextUrl(PaymentHelper::getCancelURL($checkoutToken))
            ->setMessage(__('Payment failed!'));
    }

    public function cancel(PaymentRequest $request, BaseHttpResponse $response)
    {
        $checkoutToken = $request->input('value_b');

        return $response
            ->setError()
            ->setNextUrl(PaymentHelper::getCancelURL($checkoutToken))
            ->setMessage(__('Payment failed!'));
    }

    public function ipn(PaymentRequest $request, BaseHttpResponse $response)
    {
        // Received all the payment information from the gateway
        // Check transaction id is posted or not.
        if (! $request->input('tran_id')) {
            return $response
                ->setError()
                ->setMessage(__('Invalid Data!'));
        }

        $transactionId = $request->input('tran_id');

        // Check order status in order table against the transaction id or order id.
        $transaction = Payment::query()->where('charge_id', $transactionId)
            ->select(['charge_id', 'status'])->first();

        if (! $transaction) {
            return $response
                ->setError()
                ->setMessage(__('Invalid Transaction!'));
        }

        if ($transaction->status == PaymentStatusEnum::PENDING) {
            $sslc = new SslCommerzNotification();
            $validation = $sslc->orderValidate(
                $request->all(),
                $transactionId,
                $transaction->amount,
                $transaction->currency
            );

            if ($validation) {
                /*
                That means IPN worked. Here you need to update order status
                in order table as Processing or Complete.
                Here you can also send sms or email for successful transaction to customer
                */
                Payment::query()
                    ->where('charge_id', $transactionId)
                    ->update(['status' => PaymentStatusEnum::COMPLETED]);

                return $response
                    ->setError()
                    ->setMessage(__('Transaction is successfully completed!'));
            }
            /*
               That means IPN worked, but Transaction validation failed.
               Here you need to update order status as Failed in order table.
               */
            Payment::query()
                ->where('charge_id', $transactionId)
                ->update(['status' => PaymentStatusEnum::FAILED]);

            return $response
                ->setError()
                ->setMessage(__('Validation Fail!'));
        }

        // That means Order status already updated. No need to update database.
        return $response
            ->setError()
            ->setMessage(__('Transaction is already successfully completed!'));
    }
}
