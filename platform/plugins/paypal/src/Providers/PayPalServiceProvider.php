<?php

namespace Guestcms\PayPal\Providers;

use Guestcms\Base\Traits\LoadAndPublishDataTrait;
use Illuminate\Support\ServiceProvider;

class PayPalServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function boot(): void
    {
        if (! is_plugin_active('payment')) {
            return;
        }

        $this->setNamespace('plugins/paypal')
            ->loadHelpers()
            ->loadRoutes()
            ->loadAndPublishViews()
            ->publishAssets();

        $this->app->register(HookServiceProvider::class);
    }
}
