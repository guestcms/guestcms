<?php

namespace Guestcms\Hotel\Facades;

use Guestcms\Hotel\Supports\CurrencySupport;
use Illuminate\Support\Facades\Facade;

/**
 * @method static void setApplicationCurrency(\Guestcms\Hotel\Models\Currency $currency)
 * @method static \Guestcms\Hotel\Models\Currency|null getApplicationCurrency()
 * @method static \Guestcms\Hotel\Models\Currency|null getDefaultCurrency()
 * @method static \Illuminate\Support\Collection currencies()
 * @method static string|null detectedCurrencyCode()
 * @method static array countryCurrencies()
 * @method static array currencyCodes()
 *
 * @see \Guestcms\Hotel\Supports\CurrencySupport
 */
class Currency extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return CurrencySupport::class;
    }
}
