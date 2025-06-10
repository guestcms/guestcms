<?php

namespace Guestcms\Captcha\Forms\Fields;

use Guestcms\Base\Forms\FormField;

class MathCaptchaField extends FormField
{
    protected function getTemplate(): string
    {
        return 'plugins/captcha::forms.fields.math-captcha';
    }
}
