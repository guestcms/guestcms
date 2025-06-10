<?php

namespace Guestcms\Blog\Providers;

use Guestcms\Blog\Listeners\RenderingSiteMapListener;
use Guestcms\Theme\Events\RenderingSiteMapEvent;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        RenderingSiteMapEvent::class => [
            RenderingSiteMapListener::class,
        ],
    ];
}
