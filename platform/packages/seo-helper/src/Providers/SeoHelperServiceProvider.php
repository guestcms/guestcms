<?php

namespace Guestcms\SeoHelper\Providers;

use Guestcms\Base\Supports\ServiceProvider;
use Guestcms\Base\Traits\LoadAndPublishDataTrait;
use Guestcms\SeoHelper\Contracts\SeoHelperContract;
use Guestcms\SeoHelper\Contracts\SeoMetaContract;
use Guestcms\SeoHelper\Contracts\SeoOpenGraphContract;
use Guestcms\SeoHelper\Contracts\SeoTwitterContract;
use Guestcms\SeoHelper\SeoHelper;
use Guestcms\SeoHelper\SeoMeta;
use Guestcms\SeoHelper\SeoOpenGraph;
use Guestcms\SeoHelper\SeoTwitter;

/**
 * @since 02/12/2015 14:09 PM
 */
class SeoHelperServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        $this->app->bind(SeoMetaContract::class, SeoMeta::class);
        $this->app->bind(SeoHelperContract::class, SeoHelper::class);
        $this->app->bind(SeoOpenGraphContract::class, SeoOpenGraph::class);
        $this->app->bind(SeoTwitterContract::class, SeoTwitter::class);
    }

    public function boot(): void
    {
        $this
            ->setNamespace('packages/seo-helper')
            ->loadHelpers()
            ->loadAndPublishConfigurations(['general'])
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->publishAssets();

        $this->app->register(EventServiceProvider::class);
        $this->app->register(HookServiceProvider::class);
    }
}
