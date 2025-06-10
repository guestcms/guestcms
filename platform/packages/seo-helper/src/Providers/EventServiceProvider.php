<?php

namespace Guestcms\SeoHelper\Providers;

use Guestcms\Base\Events\CreatedContentEvent;
use Guestcms\Base\Events\DeletedContentEvent;
use Guestcms\Base\Events\UpdatedContentEvent;
use Guestcms\SeoHelper\Listeners\CreatedContentListener;
use Guestcms\SeoHelper\Listeners\DeletedContentListener;
use Guestcms\SeoHelper\Listeners\UpdatedContentListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
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
