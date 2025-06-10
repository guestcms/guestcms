<?php

namespace Guestcms\Slug\Forms\Fields;

use Guestcms\Base\Forms\FormField;

class PermalinkField extends FormField
{
    protected function getTemplate(): string
    {
        return 'packages/slug::forms.fields.permalink';
    }
}
