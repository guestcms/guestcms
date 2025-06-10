<?php

namespace Guestcms\Base\Forms\Fields;

use Guestcms\Base\Forms\FormField;

class HiddenField extends FormField
{
    protected function getTemplate(): string
    {
        return 'core/base::forms.fields.hidden';
    }
}
