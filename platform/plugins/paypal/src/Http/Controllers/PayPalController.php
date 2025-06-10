<?php

namespace Guestcms\PayPal\Http\Controllers;

use Guestcms\Base\Http\Controllers\BaseController;
use Guestcms\Base\Http\Responses\BaseHttpResponse;
use Guestcms\Payment\Supports\PaymentHelper;
use Guestcms\PayPal\Http\Requests\PayPalPaymentCallbackRequest;
use Guestcms\PayPal\Services\Gateways\PayPalPaymentService;

class PayPalController extends BaseController
{
    public function getCallback(
        PayPalPaymentCallbackRequest $request,
        PayPalPaymentService $payPalPaymentService,
        BaseHttpResponse $response
    ) {
        $status = $payPalPaymentService->getPaymentStatus($request);

        if (! $status) {
            return $response
                ->setError()
                ->setNextUrl(PaymentHelper::getCancelURL())
                ->withInput()
                ->setMessage(__('Payment failed!'));
        }

        $payPalPaymentService->afterMakePayment($request->input());

        return $response
            ->setNextUrl(PaymentHelper::getRedirectURL())
            ->setMessage(__('Checkout successfully!'));
    }
}
