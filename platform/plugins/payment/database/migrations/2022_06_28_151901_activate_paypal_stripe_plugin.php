<?php

use Guestcms\PluginManagement\Services\PluginService;
use Illuminate\Database\Migrations\Migration;

return new class () extends Migration {
    public function up(): void
    {
        $pluginService = app(PluginService::class);
        $pluginService->activate('stripe');
        $pluginService->activate('paypal');
    }
};
