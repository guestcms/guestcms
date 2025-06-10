<?php

namespace Guestcms\Base\Forms\Fields;

use Guestcms\Base\Forms\FormField;

class RepeaterField extends FormField
{
    protected function getTemplate(): string
    {
        return 'core/base::forms.fields.repeater';
    }
}
