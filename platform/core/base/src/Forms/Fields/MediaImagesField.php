<?php

namespace Guestcms\Base\Forms\Fields;

use Guestcms\Base\Facades\Assets;
use Guestcms\Base\Forms\FormField;

class MediaImagesField extends FormField
{
    protected function getTemplate(): string
    {
        Assets::addScripts(['jquery-ui']);

        return 'core/base::forms.fields.media-images';
    }
}
