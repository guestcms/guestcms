<?php

namespace Guestcms\SslCommerz\Services;

use Guestcms\SslCommerz\Library\SslCommerz\SslCommerzNotification;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class SslCommerz extends SslCommerzNotification
{
    public function refundOrder($paymentId, $amount, array $options = []): array
    {
        $this->setApiUrl($this->config['apiDomain'] . $this->config['apiUrl']['refund_payment']);

        $requestData = [
            'bank_tran_id' => $paymentId,
            'refund_amount' => number_format($amount, 2, '.', ''),
            'refund_remarks' => Arr::get($options, 'refund_note', ''),
        ];

        $this->data = array_merge($this->data, $requestData);

        $this->setAuthenticationInfo();

        return $this->callApi();
    }

    public function refundDetail(string $refundRefId): array
    {
        $this->setApiUrl($this->config['apiDomain'] . $this->config['apiUrl']['refund_status']);

        $requestData = [
            'refund_ref_id' => $refundRefId,
        ];

        $this->data = array_merge($this->data, $requestData);

        $this->setAuthenticationInfo();

        return $this->callApi();
    }

    public function getPaymentDetails(string $transactionId): array
    {
        $this->setApiUrl($this->config['apiDomain'] . $this->config['apiUrl']['refund_payment']);

        $this->data['tran_id'] = $transactionId;
        $this->data['format'] = 'json';

        $this->setAuthenticationInfo();

        return $this->callApi();
    }

    public function callApi(): array
    {
        if (! $this->getStoreId() || ! $this->getStorePassword()) {
            throw new Exception('Missing store ID or password!');
        }

        do_action('payment_before_making_api_request', SSLCOMMERZ_PAYMENT_METHOD_NAME, $this->data);

        $response = Http::get($this->getApiUrl(), [
            'query' => $this->data,
        ]);

        do_action('payment_after_api_response', SSLCOMMERZ_PAYMENT_METHOD_NAME, $this->data, $response->json());

        $data = $response->json();
        $status = Arr::get($data, 'APIConnect');

        switch ($status) {
            case 'DONE':
                break;
            case 'INVALID_REQUEST':
                throw new Exception('Invalid data imputed to call the API, APIConnect: ' . $status);
            case 'FAILED':
                throw new Exception('API Authentication Failed, APIConnect: ' . $status);
            case 'INACTIVE':
                throw new Exception('API User/Store ID is Inactive, APIConnect: ' . $status);
            default:
                throw new Exception('Cannot get APIConnect');
        }

        return $data;
    }
}
