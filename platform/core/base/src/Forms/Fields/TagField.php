<?php

namespace Guestcms\Base\Forms\Fields;

use Guestcms\Base\Facades\Assets;
use Guestcms\Base\Forms\FormField;

class TagField extends FormField
{
    protected function getTemplate(): string
    {
        Assets::addStyles('tagify')
            ->addScripts('tagify')
            ->addScriptsDirectly('vendor/core/core/base/js/tags.js');

        return 'core/base::forms.fields.tags';
    }
}
