<?php

namespace Guestcms\Language\Forms\Fields;

use Guestcms\Base\Forms\FormField;

class LanguageSwitcherField extends FormField
{
    protected function getTemplate(): string
    {
        return 'plugins/language::forms.fields.language-switcher';
    }
}
