<?php

namespace Guestcms\Paystack\Providers;

use Guestcms\Base\Traits\LoadAndPublishDataTrait;
use Illuminate\Support\ServiceProvider;

class PaystackServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function boot(): void
    {
        if (! is_plugin_active('payment')) {
            return;
        }

        $this->setNamespace('plugins/paystack')
            ->loadHelpers()
            ->loadRoutes()
            ->loadAndPublishViews()
            ->publishAssets();

        $this->app->register(HookServiceProvider::class);

        $config = $this->app['config'];

        $config->set([
            'paystack.publicKey' => get_payment_setting('public', PAYSTACK_PAYMENT_METHOD_NAME),
            'paystack.secretKey' => get_payment_setting('secret', PAYSTACK_PAYMENT_METHOD_NAME),
            'paystack.paymentUrl' => 'https://api.paystack.co',
        ]);
    }
}
