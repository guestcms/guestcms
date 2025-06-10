<?php

namespace Guestcms\Base\Forms\Fields;

use Guestcms\Base\Facades\Assets;
use Guestcms\Base\Forms\FormField;

class TimePickerField extends FormField
{
    protected function getTemplate(): string
    {
        Assets::addScripts(['timepicker'])
            ->addStyles(['timepicker']);

        return 'core/base::forms.fields.time-picker';
    }
}
