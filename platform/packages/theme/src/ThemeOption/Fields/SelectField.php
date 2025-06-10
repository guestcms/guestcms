<?php

namespace Guestcms\Theme\ThemeOption\Fields;

use Guestcms\Theme\Concerns\ThemeOption\Fields\HasOptions;
use Guestcms\Theme\ThemeOption\ThemeOptionField;

class SelectField extends ThemeOptionField
{
    use HasOptions;

    public function fieldType(): string
    {
        return 'customSelect';
    }

    public function toArray(): array
    {
        return [
            ...parent::toArray(),
            'attributes' => [
                ...parent::toArray()['attributes'],
                'choices' => $this->options,
            ],
        ];
    }
}
