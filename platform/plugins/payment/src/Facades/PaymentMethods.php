<?php

namespace Guestcms\Payment\Facades;

use Guestcms\Payment\Supports\PaymentMethods as PaymentMethodsSupport;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \Guestcms\Payment\Supports\PaymentMethods method(string $name, array $args = [])
 * @method static array methods()
 * @method static string|null getDefaultMethod()
 * @method static string|null getSelectedMethod()
 * @method static string|null getSelectingMethod()
 * @method static string render()
 *
 * @see \Guestcms\Payment\Supports\PaymentMethods
 */
class PaymentMethods extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return PaymentMethodsSupport::class;
    }
}
