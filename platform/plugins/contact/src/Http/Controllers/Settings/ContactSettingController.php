<?php

namespace Guestcms\Contact\Http\Controllers\Settings;

use Guestcms\Base\Http\Responses\BaseHttpResponse;
use Guestcms\Contact\Forms\Settings\ContactSettingForm;
use Guestcms\Contact\Http\Requests\Settings\ContactSettingRequest;
use Guestcms\Setting\Http\Controllers\SettingController;
use Illuminate\Support\Arr;

class ContactSettingController extends SettingController
{
    public function edit()
    {
        $this->pageTitle(trans('plugins/contact::contact.settings.title'));

        return ContactSettingForm::create()->renderForm();
    }

    public function update(ContactSettingRequest $request): BaseHttpResponse
    {
        return $this->performUpdate(Arr::except($request->validated(), [
            'receiver_emails_for_validation',
            'blacklist_keywords_for_validation',
        ]));
    }
}
