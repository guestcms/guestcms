<?php

namespace Guestcms\Theme\Forms\Fields;

use Guestcms\Base\Facades\Assets;
use Guestcms\Base\Forms\FormField;

class ThemeIconField extends FormField
{
    protected function getTemplate(): string
    {
        Assets::addScriptsDirectly('vendor/core/packages/theme/js/icons-field.js');

        return 'packages/theme::fields.icons-field';
    }
}
