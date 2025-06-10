<?php

namespace Guestcms\Captcha\Http\Controllers\Settings;

use Guestcms\Base\Http\Responses\BaseHttpResponse;
use Guestcms\Captcha\Forms\CaptchaSettingForm;
use Guestcms\Captcha\Http\Requests\Settings\CaptchaSettingRequest;
use Guestcms\Setting\Http\Controllers\SettingController;

class CaptchaSettingController extends SettingController
{
    public function edit()
    {
        $this->pageTitle(trans('plugins/captcha::captcha.settings.title'));

        return CaptchaSettingForm::create()->renderForm();
    }

    public function update(CaptchaSettingRequest $request): BaseHttpResponse
    {
        $request->merge([
            'enable_math_captcha_for_contact_form' => $request->input('enable_math_captcha_guestcms_contact_forms_fronts_contact_form'),
            'enable_math_captcha_for_newsletter_form' => $request->input('enable_math_captcha_guestcms_newsletter_forms_fronts_newsletter_form'),
        ]);

        return $this->performUpdate($request->validated());
    }
}
