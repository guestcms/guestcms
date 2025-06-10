<?php

namespace Guestcms\ACL\Providers;

use Guestcms\ACL\Events\RoleAssignmentEvent;
use Guestcms\ACL\Events\RoleUpdateEvent;
use Guestcms\ACL\Listeners\LoginListener;
use Guestcms\ACL\Listeners\RoleAssignmentListener;
use Guestcms\ACL\Listeners\RoleUpdateListener;
use Illuminate\Auth\Events\Login;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        RoleUpdateEvent::class => [
            RoleUpdateListener::class,
        ],
        RoleAssignmentEvent::class => [
            RoleAssignmentListener::class,
        ],
        Login::class => [
            LoginListener::class,
        ],
    ];
}
