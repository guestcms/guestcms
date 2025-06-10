<?php

namespace Guestcms\Table\Providers;

use Guestcms\Base\Supports\ServiceProvider;
use Guestcms\Base\Traits\LoadAndPublishDataTrait;
use Guestcms\Table\ApiResourceDataTable;
use Guestcms\Table\CollectionDataTable;
use Guestcms\Table\EloquentDataTable;
use Guestcms\Table\QueryDataTable;

class TableServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function boot(): void
    {
        $this
            ->setNamespace('core/table')
            ->loadHelpers()
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->loadRoutes()
            ->publishAssets();

        $this->app['config']->set([
            'datatables.engines' => [
                'eloquent' => EloquentDataTable::class,
                'query' => QueryDataTable::class,
                'collection' => CollectionDataTable::class,
                'resource' => ApiResourceDataTable::class,
            ],
        ]);
    }
}
