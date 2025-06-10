<?php

namespace Guestcms\Testimonial\Providers;

use Guestcms\Base\Facades\DashboardMenu;
use Guestcms\Base\Supports\ServiceProvider;
use Guestcms\Base\Traits\LoadAndPublishDataTrait;
use Guestcms\LanguageAdvanced\Supports\LanguageAdvancedManager;
use Guestcms\Testimonial\Models\Testimonial;
use Guestcms\Testimonial\Repositories\Eloquent\TestimonialRepository;
use Guestcms\Testimonial\Repositories\Interfaces\TestimonialInterface;

class TestimonialServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        $this->app->bind(TestimonialInterface::class, function () {
            return new TestimonialRepository(new Testimonial());
        });
    }

    public function boot(): void
    {
        $this
            ->setNamespace('plugins/testimonial')
            ->loadHelpers()
            ->loadAndPublishConfigurations(['permissions'])
            ->loadMigrations()
            ->loadAndPublishTranslations()
            ->loadRoutes();

        if (defined('LANGUAGE_MODULE_SCREEN_NAME') && defined('LANGUAGE_ADVANCED_MODULE_SCREEN_NAME')) {
            LanguageAdvancedManager::registerModule(Testimonial::class, [
                'name',
                'content',
                'company',
            ]);
        }

        DashboardMenu::beforeRetrieving(function (): void {
            DashboardMenu::make()
                ->registerItem([
                    'id' => 'cms-plugins-testimonial',
                    'priority' => 5,
                    'name' => 'plugins/testimonial::testimonial.name',
                    'icon' => 'ti ti-user-star',
                    'url' => route('testimonial.index'),
                    'permissions' => ['testimonial.index'],
            ]);
        });
    }
}
