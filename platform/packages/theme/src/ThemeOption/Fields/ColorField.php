<?php

namespace Guestcms\Theme\ThemeOption\Fields;

use Guestcms\Theme\ThemeOption\ThemeOptionField;

class ColorField extends ThemeOptionField
{
    public function fieldType(): string
    {
        return 'customColor';
    }

    public function toArray(): array
    {
        return [
            ...parent::toArray(),
            'attributes' => [
                ...parent::toArray()['attributes'],
                'value' => $this->getValue(),
            ],
        ];
    }
}
