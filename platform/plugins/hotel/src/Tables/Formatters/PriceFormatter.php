<?php

namespace Guestcms\Hotel\Tables\Formatters;

use Guestcms\Table\Formatter;

class PriceFormatter implements Formatter
{
    public function format($value, $row): string
    {
        return format_price($value);
    }
}
