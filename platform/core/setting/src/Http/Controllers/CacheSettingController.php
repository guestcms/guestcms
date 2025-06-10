<?php

namespace Guestcms\Setting\Http\Controllers;

use Guestcms\Base\Http\Responses\BaseHttpResponse;
use Guestcms\Setting\Forms\CacheSettingForm;
use Guestcms\Setting\Http\Requests\CacheSettingRequest;

class CacheSettingController extends SettingController
{
    public function edit()
    {
        $this->pageTitle(trans('core/setting::setting.cache.title'));

        return CacheSettingForm::create()->renderForm();
    }

    public function update(CacheSettingRequest $request): BaseHttpResponse
    {
        return $this->performUpdate($request->validated());
    }
}
