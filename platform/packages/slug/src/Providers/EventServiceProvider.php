<?php

namespace Guestcms\Slug\Providers;

use Guestcms\Base\Events\CreatedContentEvent;
use Guestcms\Base\Events\DeletedContentEvent;
use Guestcms\Base\Events\FinishedSeederEvent;
use Guestcms\Base\Events\SeederPrepared;
use Guestcms\Base\Events\UpdatedContentEvent;
use Guestcms\Slug\Listeners\CreatedContentListener;
use Guestcms\Slug\Listeners\CreateMissingSlug;
use Guestcms\Slug\Listeners\DeletedContentListener;
use Guestcms\Slug\Listeners\TruncateSlug;
use Guestcms\Slug\Listeners\UpdatedContentListener;
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
        SeederPrepared::class => [
            TruncateSlug::class,
        ],
        FinishedSeederEvent::class => [
            CreateMissingSlug::class,
        ],
    ];
}
