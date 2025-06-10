<?php

namespace Guestcms\Hotel\Enums;

use Guestcms\Base\Facades\BaseHelper;
use Guestcms\Base\Supports\Enum;
use Illuminate\Support\HtmlString;

/**
 * @method static \Guestcms\Hotel\Enums\CouponTypeEnum PERCENTAGE()
 * @method static \Guestcms\Hotel\Enums\CouponTypeEnum FIXED()
 */
class CouponTypeEnum extends Enum
{
    public const PERCENTAGE = 'percentage';

    public const FIXED = 'fixed';

    public static $langPath = 'plugins/hotel::coupon.types';

    public function toHtml(): HtmlString|string
    {
        $color = match ($this->value) {
            self::PERCENTAGE => 'info',
            self::FIXED => 'success',
            default => 'primary',
        };

        return BaseHelper::renderBadge($this->label(), $color);
    }
}
