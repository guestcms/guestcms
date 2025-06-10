<?php

namespace Guestcms\Newsletter\Enums;

use Guestcms\Base\Facades\BaseHelper;
use Guestcms\Base\Supports\Enum;
use Illuminate\Support\HtmlString;

/**
 * @method static NewsletterStatusEnum SUBSCRIBED()
 * @method static NewsletterStatusEnum UNSUBSCRIBED()
 */
class NewsletterStatusEnum extends Enum
{
    public const SUBSCRIBED = 'subscribed';

    public const UNSUBSCRIBED = 'unsubscribed';

    public static $langPath = 'plugins/newsletter::newsletter.statuses';

    public function toHtml(): HtmlString|string
    {
        $color = match ($this->value) {
            self::SUBSCRIBED => 'success',
            self::UNSUBSCRIBED => 'warning',
            default => 'primary',
        };

        return BaseHelper::renderBadge($this->label(), $color);
    }
}
