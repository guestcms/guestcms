<?php

namespace Guestcms\Base\Forms\Fields;

use Guestcms\Base\Forms\FormField;

class CodeEditorField extends FormField
{
    protected function getTemplate(): string
    {
        return 'core/base::forms.fields.code-editor';
    }
}
