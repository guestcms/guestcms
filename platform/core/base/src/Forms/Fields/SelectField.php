<?php

namespace Guestcms\Base\Forms\Fields;

use Guestcms\Base\Forms\FieldTypes\SelectType;

class SelectField extends SelectType
{
    protected function getTemplate(): string
    {
        return 'core/base::forms.fields.custom-select';
    }

    public function getDefaults(): array
    {
        return [
            'choices' => [],
            'option_attributes' => [],
            'empty_value' => null,
            'selected' => null,
        ];
    }
}
