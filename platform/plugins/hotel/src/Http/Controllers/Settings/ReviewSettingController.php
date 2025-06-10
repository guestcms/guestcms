<?php

namespace Guestcms\Hotel\Http\Controllers\Settings;

use Guestcms\Hotel\Forms\Settings\ReviewSettingForm;
use Guestcms\Hotel\Http\Requests\Settings\ReviewSettingRequest;
use Guestcms\Setting\Http\Controllers\SettingController;

class ReviewSettingController extends SettingController
{
    public function edit()
    {
        $this->pageTitle(trans('plugins/hotel::settings.review.title'));

        return ReviewSettingForm::create()->renderForm();
    }

    public function update(ReviewSettingRequest $request)
    {
        return $this->performUpdate($request->validated());
    }
}
