<?php

namespace Guestcms\Installer\Http\Requests;

use Guestcms\Installer\InstallerStep\InstallerStep;
use Guestcms\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class ChooseThemeRequest extends Request
{
    public function rules(): array
    {
        return [
            'theme' => ['required', 'string', Rule::in(array_keys(InstallerStep::getThemes()))],
        ];
    }
}
