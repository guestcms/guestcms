<?php

namespace Guestcms\Newsletter\Http\Controllers\Settings;

use Guestcms\Base\Http\Responses\BaseHttpResponse;
use Guestcms\Newsletter\Forms\NewsletterSettingForm;
use Guestcms\Newsletter\Http\Requests\Settings\NewsletterSettingRequest;
use Guestcms\Setting\Http\Controllers\SettingController;

class NewsletterSettingController extends SettingController
{
    public function edit()
    {
        $this->pageTitle(trans('plugins/newsletter::newsletter.settings.title'));

        return NewsletterSettingForm::create()->renderForm();
    }

    public function update(NewsletterSettingRequest $request): BaseHttpResponse
    {
        return $this->performUpdate($request->validated());
    }
}
