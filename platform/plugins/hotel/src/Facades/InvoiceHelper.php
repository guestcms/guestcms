<?php

namespace Guestcms\Hotel\Facades;

use Guestcms\Hotel\Supports\InvoiceHelper as BaseInvoiceHelper;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \Guestcms\Hotel\Models\Invoice store(\Guestcms\Hotel\Models\Booking $booking)
 * @method static array getVariables()
 * @method static \Guestcms\Hotel\Models\Invoice getDataForPreview()
 * @method static \Illuminate\Http\Response downloadInvoice($invoice)
 * @method static \Illuminate\Http\Response streamInvoice(\Guestcms\Hotel\Models\Invoice $invoice)
 * @method static \Barryvdh\DomPDF\PDF makeInvoice(\Guestcms\Hotel\Models\Invoice $invoice)
 * @method static string getInvoiceTemplate()
 * @method static string getInvoiceTemplatePath()
 * @method static string getInvoiceTemplateCustomizedPath()
 * @method static string getLanguageSupport()
 *
 * @see \Guestcms\Hotel\Supports\InvoiceHelper
 */
class InvoiceHelper extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return BaseInvoiceHelper::class;
    }
}
