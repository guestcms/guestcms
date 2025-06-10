<?php

namespace Guestcms\Support\Providers;

use Guestcms\Base\Supports\ServiceProvider;

class SupportServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app['files']->requireOnce(core_path('support/helpers/common.php'));
    }
}
