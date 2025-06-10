<?php

namespace Guestcms\Language\Http\Controllers\Settings;

use Guestcms\Base\Http\Responses\BaseHttpResponse;
use Guestcms\Language\Http\Requests\Settings\LanguageSettingRequest;
use Guestcms\Setting\Http\Controllers\SettingController;

class LanguageSettingController extends SettingController
{
    public function update(LanguageSettingRequest $request): BaseHttpResponse
    {
        return $this->performUpdate([
            ...$request->validated(),
            'language_hide_languages' => $request->input('language_hide_languages', []),
        ]);
    }
}
