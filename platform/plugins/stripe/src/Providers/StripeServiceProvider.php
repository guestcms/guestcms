<?php

namespace Guestcms\Stripe\Providers;

use Guestcms\Base\Traits\LoadAndPublishDataTrait;
use Illuminate\Support\ServiceProvider;

class StripeServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function boot(): void
    {
        if (! is_plugin_active('payment')) {
            return;
        }

        $this->setNamespace('plugins/stripe')
            ->loadHelpers()
            ->loadRoutes()
            ->loadAndPublishTranslations()
            ->loadAndPublishViews()
            ->publishAssets();

        $this->app->register(HookServiceProvider::class);
    }
}
