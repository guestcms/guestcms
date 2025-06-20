<?php

namespace Guestcms\Payment\Enums;

use Guestcms\Base\Facades\BaseHelper;
use Guestcms\Base\Supports\Enum;
use Illuminate\Support\HtmlString;

/**
 * @method static PaymentStatusEnum PENDING()
 * @method static PaymentStatusEnum COMPLETED()
 * @method static PaymentStatusEnum REFUNDING()
 * @method static PaymentStatusEnum REFUNDED()
 * @method static PaymentStatusEnum FRAUD()
 * @method static PaymentStatusEnum FAILED()
 */
class PaymentStatusEnum extends Enum
{
    public const PENDING = 'pending';

    public const COMPLETED = 'completed';

    public const REFUNDING = 'refunding';

    public const REFUNDED = 'refunded';

    public const FRAUD = 'fraud';

    public const FAILED = 'failed';

    public const CANCELED = 'canceled';

    public static $langPath = 'plugins/payment::payment.statuses';

    public function toHtml(): HtmlString|string
    {
        $color = match ($this->value) {
            self::PENDING, self::REFUNDING => 'warning',
            self::COMPLETED => 'success',
            self::REFUNDED => 'info',
            self::FRAUD, self::FAILED, self::CANCELED => 'danger',
            default => 'primary',
        };

        return BaseHelper::renderBadge($this->label(), $color);
    }
}
