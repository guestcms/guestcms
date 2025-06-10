<?php

namespace Guestcms\Base\Http\Controllers;

use Guestcms\Base\Supports\Breadcrumb;

class BaseSystemController extends BaseController
{
    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(
                trans('core/base::base.panel.system'),
                route('system.index')
            );
    }
}
