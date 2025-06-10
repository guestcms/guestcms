<?php

namespace Guestcms\Blog\Http\Controllers\Settings;

use Guestcms\Base\Http\Responses\BaseHttpResponse;
use Guestcms\Blog\Forms\Settings\BlogSettingForm;
use Guestcms\Blog\Http\Requests\Settings\BlogSettingRequest;
use Guestcms\Setting\Http\Controllers\SettingController;

class BlogSettingController extends SettingController
{
    public function edit()
    {
        $this->pageTitle(trans('plugins/blog::base.settings.title'));

        return BlogSettingForm::create()->renderForm();
    }

    public function update(BlogSettingRequest $request): BaseHttpResponse
    {
        return $this->performUpdate($request->validated());
    }
}
