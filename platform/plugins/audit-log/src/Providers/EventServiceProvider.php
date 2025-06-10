<?php

namespace Guestcms\AuditLog\Providers;

use Guestcms\AuditLog\Events\AuditHandlerEvent;
use Guestcms\AuditLog\Listeners\AuditHandlerListener;
use Guestcms\AuditLog\Listeners\CreatedContentListener;
use Guestcms\AuditLog\Listeners\CustomerLoginListener;
use Guestcms\AuditLog\Listeners\CustomerLogoutListener;
use Guestcms\AuditLog\Listeners\CustomerRegistrationListener;
use Guestcms\AuditLog\Listeners\DeletedContentListener;
use Guestcms\AuditLog\Listeners\LoginListener;
use Guestcms\AuditLog\Listeners\UpdatedContentListener;
use Guestcms\Base\Events\CreatedContentEvent;
use Guestcms\Base\Events\DeletedContentEvent;
use Guestcms\Base\Events\UpdatedContentEvent;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        AuditHandlerEvent::class => [
            AuditHandlerListener::class,
        ],
        Login::class => [
            LoginListener::class,
            CustomerLoginListener::class,
        ],
        Logout::class => [
            CustomerLogoutListener::class,
        ],
        Registered::class => [
            CustomerRegistrationListener::class,
        ],
        UpdatedContentEvent::class => [
            UpdatedContentListener::class,
        ],
        CreatedContentEvent::class => [
            CreatedContentListener::class,
        ],
        DeletedContentEvent::class => [
            DeletedContentListener::class,
        ],
    ];
}
