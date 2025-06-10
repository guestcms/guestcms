<?php

namespace Guestcms\Gallery\Providers;

use Guestcms\Base\Events\CreatedContentEvent;
use Guestcms\Base\Events\DeletedContentEvent;
use Guestcms\Base\Events\UpdatedContentEvent;
use Guestcms\Gallery\Listeners\CreatedContentListener;
use Guestcms\Gallery\Listeners\DeletedContentListener;
use Guestcms\Gallery\Listeners\RenderingSiteMapListener;
use Guestcms\Gallery\Listeners\UpdatedContentListener;
use Guestcms\Theme\Events\RenderingSiteMapEvent;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        RenderingSiteMapEvent::class => [
            RenderingSiteMapListener::class,
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
