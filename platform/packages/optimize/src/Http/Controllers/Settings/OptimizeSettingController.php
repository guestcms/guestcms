<?php

namespace Guestcms\Optimize\Http\Controllers\Settings;

use Guestcms\Base\Http\Responses\BaseHttpResponse;
use Guestcms\Optimize\Forms\Settings\OptimizeSettingForm;
use Guestcms\Optimize\Http\Requests\OptimizeSettingRequest;
use Guestcms\Setting\Http\Controllers\SettingController;

class OptimizeSettingController extends SettingController
{
    public function edit()
    {
        $this->pageTitle(trans('packages/optimize::optimize.settings.title'));

        return OptimizeSettingForm::create()->renderForm();
    }

    public function update(OptimizeSettingRequest $request): BaseHttpResponse
    {
        return $this->performUpdate($request->validated());
    }
}
