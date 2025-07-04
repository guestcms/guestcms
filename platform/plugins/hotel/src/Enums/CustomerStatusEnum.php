<?php

namespace Guestcms\Hotel\Enums;

use Guestcms\Base\Facades\BaseHelper;
use Guestcms\Base\Supports\Enum;
use Illuminate\Support\HtmlString;

/**
 * @method static CustomerStatusEnum ACTIVATED()
 * @method static CustomerStatusEnum LOCKED()
 */
class CustomerStatusEnum extends Enum
{
    public const ACTIVATED = 'activated';
    public const LOCKED = 'locked';

    public static $langPath = 'plugins/hotel::customer.statuses';

    public function toHtml(): HtmlString|string
    {
        $color = match ($this->value) {
            self::ACTIVATED => 'success',
            self::LOCKED => 'danger',
            default => 'primary',
        };

        return BaseHelper::renderBadge($this->label(), $color);
    }
}
