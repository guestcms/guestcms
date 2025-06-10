<?php

namespace Guestcms\Base\Forms\Fields;

use Guestcms\Base\Forms\FieldTypes\CheckableType;

class CheckboxField extends CheckableType
{
    protected function getTemplate(): string
    {
        return 'core/base::forms.fields.checkbox';
    }
}
