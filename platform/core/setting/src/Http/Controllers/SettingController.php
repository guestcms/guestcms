<?php

namespace Guestcms\Setting\Http\Controllers;

use Guestcms\Base\Http\Controllers\BaseController;
use Guestcms\Base\Supports\Breadcrumb;
use Guestcms\Setting\Http\Controllers\Concerns\InteractsWithSettings;

abstract class SettingController extends BaseController
{
    use InteractsWithSettings;

    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('core/setting::setting.title'), route('settings.index'));
    }
}
