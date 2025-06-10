<?php

namespace Guestcms\Theme\ThemeOption\Fields;

use Guestcms\Theme\ThemeOption\ThemeOptionField;

class MediaImageField extends ThemeOptionField
{
    public function fieldType(): string
    {
        return 'mediaImage';
    }
}
