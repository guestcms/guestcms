<?php

namespace Guestcms\Analytics\Exceptions;

use Guestcms\Base\Facades\Html;
use Exception;

class InvalidConfiguration extends Exception
{
    public static function credentialsIsNotValid(): self
    {
        return new self(trans('plugins/analytics::analytics.settings.credential_is_not_valid', ['url' => Html::link('https://docs.guestcms.com/cms/usage-analytics.html', attributes: ['target' => '_blank'])->toHtml()]));
    }

    public static function invalidPropertyId(): self
    {
        return new self(trans('plugins/analytics::analytics.settings.property_id_is_invalid'));
    }
}
