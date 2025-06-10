<?php

namespace Guestcms\Base\Forms\Fields;

use Guestcms\Base\Forms\FormField;

class MediaFileField extends FormField
{
    protected function getTemplate(): string
    {
        return 'core/base::forms.fields.media-file';
    }
}
