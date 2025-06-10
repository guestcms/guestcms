<?php

namespace Guestcms\Installer\Http\Requests;

use Guestcms\Installer\InstallerStep\InstallerStep;
use Guestcms\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class ChooseThemePresetRequest extends Request
{
    public function rules(): array
    {
        return [
            'theme_preset' => ['required', 'string', Rule::in(array_keys(InstallerStep::getThemePresets()))],
        ];
    }
}
