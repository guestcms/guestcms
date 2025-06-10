<?php

namespace Guestcms\Base\Forms\Fields;

use Guestcms\Base\Forms\FieldTypes\FormField;

class MediaImageField extends FormField
{
    protected bool $useDefaultFieldClass = false;

    protected function getTemplate(): string
    {
        return 'core/base::forms.fields.media-image';
    }
}
