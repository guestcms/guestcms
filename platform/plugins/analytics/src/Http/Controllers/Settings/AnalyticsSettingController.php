<?php

namespace Guestcms\Analytics\Http\Controllers\Settings;

use Guestcms\Analytics\Forms\AnalyticsSettingForm;
use Guestcms\Analytics\Http\Requests\Settings\AnalyticsSettingRequest;
use Guestcms\Base\Http\Responses\BaseHttpResponse;
use Guestcms\Setting\Http\Controllers\SettingController;

class AnalyticsSettingController extends SettingController
{
    public function edit()
    {
        $this->pageTitle(trans('plugins/analytics::analytics.settings.title'));

        return AnalyticsSettingForm::create()->renderForm();
    }

    public function update(AnalyticsSettingRequest $request): BaseHttpResponse
    {
        return $this->performUpdate($request->validated());
    }
}
