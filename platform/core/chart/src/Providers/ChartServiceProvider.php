<?php

namespace Guestcms\Chart\Providers;

use Guestcms\Base\Supports\ServiceProvider;
use Guestcms\Base\Traits\LoadAndPublishDataTrait;

class ChartServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function boot(): void
    {
        $this
            ->setNamespace('core/chart')
            ->loadAndPublishViews();
    }
}
