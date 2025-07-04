<?php

namespace Guestcms\SocialLogin\Http\Controllers\Settings;

use Guestcms\Base\Http\Responses\BaseHttpResponse;
use Guestcms\Setting\Http\Controllers\SettingController;
use Guestcms\SocialLogin\Facades\SocialService;
use Guestcms\SocialLogin\Forms\SocialLoginSettingForm;
use Guestcms\SocialLogin\Http\Requests\Settings\SocialLoginSettingRequest;
use Illuminate\Support\Arr;

class SocialLoginSettingController extends SettingController
{
    public function edit()
    {
        $this->pageTitle(trans('plugins/social-login::social-login.settings.title'));

        return SocialLoginSettingForm::create()->renderForm();
    }

    public function update(SocialLoginSettingRequest $request): BaseHttpResponse
    {
        $prefix = 'social_login_';

        $data = [
            "{$prefix}enable" => $request->input("{$prefix}enable"),
            "{$prefix}style" => $request->input("{$prefix}style"),
        ];

        foreach (SocialService::getProviders() as $provider => $item) {
            $prefix = 'social_login_' . $provider . '_';

            $data["{$prefix}enable"] = $request->input("{$prefix}enable");

            if ($provider === 'google') {
                $data["{$prefix}use_google_button"] = $request->boolean("{$prefix}use_google_button");
            }

            foreach ($item['data'] as $input) {
                if (
                    ! in_array(app()->environment(), SocialService::getEnvDisableData()) ||
                    ! in_array($input, Arr::get($item, 'disable', []))
                ) {
                    $data["{$prefix}{$input}"] = $request->input("{$prefix}{$input}");
                }
            }
        }

        return $this->performUpdate($data);
    }
}
