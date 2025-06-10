<?php

namespace Guestcms\Menu\Providers;

use Guestcms\Base\Events\DeletedContentEvent;
use Guestcms\Menu\Listeners\DeleteMenuNodeListener;
use Guestcms\Menu\Listeners\UpdateMenuNodeUrlListener;
use Guestcms\Slug\Events\UpdatedSlugEvent;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        UpdatedSlugEvent::class => [
            UpdateMenuNodeUrlListener::class,
        ],
        DeletedContentEvent::class => [
            DeleteMenuNodeListener::class,
        ],
    ];
}
