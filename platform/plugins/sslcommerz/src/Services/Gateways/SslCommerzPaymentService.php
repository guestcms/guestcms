<?php

namespace Guestcms\SslCommerz\Services\Gateways;

use Guestcms\SslCommerz\Services\Abstracts\SslCommerzPaymentAbstract;
use Illuminate\Http\Request;

class SslCommerzPaymentService extends SslCommerzPaymentAbstract
{
    public function makePayment(Request $request)
    {
    }

    public function afterMakePayment(Request $request)
    {
    }
}
