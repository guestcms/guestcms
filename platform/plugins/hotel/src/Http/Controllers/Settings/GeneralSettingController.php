<?php

namespace Guestcms\Hotel\Http\Controllers\Settings;

use Guestcms\Hotel\Forms\Settings\GeneralSettingForm;
use Guestcms\Hotel\Http\Requests\Settings\GeneralSettingRequest;
use Guestcms\Setting\Http\Controllers\SettingController;

class GeneralSettingController extends SettingController
{
    public function edit()
    {
        $this->pageTitle(trans('plugins/hotel::settings.general.title'));

        return GeneralSettingForm::create()->renderForm();
    }

    public function update(GeneralSettingRequest $request)
    {
        return $this->performUpdate($request->validated());
    }
}
