<?php

namespace Guestcms\Stripe\Services\Abstracts;

use Guestcms\Payment\Services\Traits\PaymentErrorTrait;
use Guestcms\Stripe\Supports\StripeHelper;
use Exception;
use Stripe\Charge;
use Stripe\Exception\ApiConnectionException;
use Stripe\Exception\ApiErrorException;
use Stripe\Exception\AuthenticationException;
use Stripe\Exception\CardException;
use Stripe\Exception\InvalidRequestException;
use Stripe\Exception\RateLimitException;
use Stripe\Refund;
use Stripe\Stripe;

abstract class StripePaymentAbstract
{
    use PaymentErrorTrait;

    protected ?string $token = null;

    protected float $amount;

    protected string $currency;

    protected string $chargeId;

    protected bool $supportRefundOnline = true;

    public function getSupportRefundOnline(): bool
    {
        return $this->supportRefundOnline;
    }

    public function execute(array $data): ?string
    {
        $this->token = request()->input('stripeToken');

        $chargeId = null;

        try {
            $chargeId = $this->makePayment($data);
        } catch (CardException $exception) {
            $this->setErrorMessageAndLogging($exception, 1); // Since it's a decline, \Stripe\Error\Card will be caught
        } catch (RateLimitException $exception) {
            $this->setErrorMessageAndLogging($exception, 2); // Too many requests made to the API too quickly
        } catch (InvalidRequestException $exception) {
            $this->setErrorMessageAndLogging($exception, 3); // Invalid parameters were supplied to Stripe's API
        } catch (AuthenticationException $exception) {
            $this->setErrorMessageAndLogging($exception, 4); // Authentication with Stripe's API failed (maybe you changed API keys recently)
        } catch (ApiConnectionException $exception) {
            $this->setErrorMessageAndLogging($exception, 5); // Network communication with Stripe failed
        } catch (ApiErrorException $exception) {
            $this->setErrorMessageAndLogging($exception, 6); // Display a very generic error to the user, and maybe send yourself an email
        } catch (Exception $exception) {
            $this->setErrorMessageAndLogging($exception, 7); // Something else happened, completely unrelated to Stripe
        }

        return $chargeId;
    }

    abstract public function makePayment(array $data): ?string;

    abstract public function afterMakePayment(string $chargeId, array $data);

    public function getPaymentDetails(string $chargeId): ?Charge
    {
        if (! $this->setClient()) {
            return null;
        }

        try {
            return Charge::retrieve($chargeId);
        } catch (Exception) {
            return null;
        }
    }

    public function setClient(): bool
    {
        $secret = get_payment_setting('secret', 'stripe');
        $clientId = get_payment_setting('client_id', 'stripe');

        if (! $secret || ! $clientId) {
            return false;
        }

        Stripe::setApiKey($secret);
        Stripe::setClientId($clientId);

        return true;
    }

    public function setCurrency($currency): static
    {
        $this->currency = $currency;

        return $this;
    }

    public function refundOrder(string $paymentId, float|string $totalAmount, array $options = []): array
    {
        if (! $this->setClient()) {
            return [
                'error' => true,
                'message' => trans('plugins/payment::payment.invalid_settings', ['name' => 'Stripe']),
            ];
        }

        $multiplier = StripeHelper::getStripeCurrencyMultiplier($this->currency);

        if ($multiplier > 1) {
            $totalAmount = (int) (round((float) $totalAmount, 2) * $multiplier);
        }

        try {
            $response = Refund::create([
                'charge' => $paymentId,
                'amount' => $totalAmount,
                'metadata' => $options,
            ]);

            if ($response->status == 'succeeded') {
                return [
                    'error' => false,
                    'message' => $response->status,
                    'data' => $response->toArray(),
                ];
            }

            return [
                'error' => true,
                'message' => trans('plugins/payment::payment.status_is_not_completed'),
            ];
        } catch (Exception $exception) {
            return [
                'error' => true,
                'message' => $exception->getMessage(),
            ];
        }
    }
}
