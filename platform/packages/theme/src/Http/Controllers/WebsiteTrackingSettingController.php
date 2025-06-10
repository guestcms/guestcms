<?php

namespace Guestcms\Theme\Http\Controllers;

use Guestcms\Setting\Http\Controllers\SettingController;
use Guestcms\Theme\Forms\Settings\WebsiteTrackingSettingForm;
use Guestcms\Theme\Http\Requests\WebsiteTrackingSettingRequest;

class WebsiteTrackingSettingController extends SettingController
{
    public function edit()
    {
        $this->pageTitle(trans('packages/theme::theme.settings.website_tracking.title'));

        return WebsiteTrackingSettingForm::create()->renderForm();
    }

    public function update(WebsiteTrackingSettingRequest $request)
    {
        return $this->performUpdate(
            $request->validated()
        )->withUpdatedSuccessMessage();
    }
}
