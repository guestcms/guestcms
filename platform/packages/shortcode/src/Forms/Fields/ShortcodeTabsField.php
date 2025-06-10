<?php

namespace Guestcms\Shortcode\Forms\Fields;

use Guestcms\Base\Forms\FormField;

class ShortcodeTabsField extends FormField
{
    protected function getTemplate(): string
    {
        return 'packages/shortcode::forms.fields.tabs';
    }

    public function getDefaults(): array
    {
        return [
            'fields' => [],
            'shortcode_attributes' => [],
            'min' => 1,
            'max' => 20,
            'key' => null,
        ];
    }
}
