<?php

namespace Guestcms\ACL\Providers;

use Guestcms\ACL\Commands\UserCreateCommand;
use Guestcms\ACL\Commands\UserPasswordCommand;
use Guestcms\Base\Supports\ServiceProvider;

class CommandServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->commands([
            UserCreateCommand::class,
            UserPasswordCommand::class,
        ]);
    }
}
