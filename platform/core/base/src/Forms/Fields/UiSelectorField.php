<?php

namespace Guestcms\Base\Forms\Fields;

use Guestcms\Base\Forms\FieldTypes\SelectType;

class UiSelectorField extends SelectType
{
    protected function getTemplate(): string
    {
        return 'core/base::forms.fields.ui-selector';
    }
}
