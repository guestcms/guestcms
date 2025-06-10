<?php

namespace Guestcms\Base\Http\Controllers\Concerns;

use Guestcms\Base\Facades\Breadcrumb as BreadcrumbFacade;
use Guestcms\Base\Supports\Breadcrumb;

trait HasBreadcrumb
{
    protected string $breadcrumbGroup = 'admin';

    protected function breadcrumb(): Breadcrumb
    {
        $breadcrumb = BreadcrumbFacade::for($this->breadcrumbGroup);

        if ($this->breadcrumbGroup === 'admin') {
            $breadcrumb->add(trans('core/dashboard::dashboard.title'), route('dashboard.index'));
        }

        return $breadcrumb;
    }
}
