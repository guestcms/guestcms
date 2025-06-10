<?php

namespace Guestcms\Contact\Enums;

use Guestcms\Base\Facades\Html;
use Guestcms\Base\Supports\Enum;
use Illuminate\Support\HtmlString;

/**
 * @method static ContactStatusEnum UNREAD()
 * @method static ContactStatusEnum READ()
 */
class ContactStatusEnum extends Enum
{
    public const READ = 'read';

    public const UNREAD = 'unread';

    public static $langPath = 'plugins/contact::contact.statuses';

    public function toHtml(): HtmlString|string
    {
        return match ($this->value) {
            self::UNREAD => Html::tag('span', self::UNREAD()->label(), ['class' => 'badge bg-warning text-warning-fg']),
            self::READ => Html::tag('span', self::READ()->label(), ['class' => 'badge bg-success text-success-fg']),
            default => parent::toHtml(),
        };
    }
}
