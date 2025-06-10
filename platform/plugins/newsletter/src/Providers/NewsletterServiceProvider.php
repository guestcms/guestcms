<?php

namespace Guestcms\Newsletter\Providers;

use Guestcms\Base\Facades\DashboardMenu;
use Guestcms\Base\Facades\EmailHandler;
use Guestcms\Base\Facades\PanelSectionManager;
use Guestcms\Base\PanelSections\PanelSectionItem;
use Guestcms\Base\Supports\DashboardMenuItem;
use Guestcms\Base\Supports\ServiceProvider;
use Guestcms\Base\Traits\LoadAndPublishDataTrait;
use Guestcms\Newsletter\Contracts\Factory;
use Guestcms\Newsletter\Forms\Fronts\NewsletterForm;
use Guestcms\Newsletter\Http\Requests\NewsletterRequest;
use Guestcms\Newsletter\Models\Newsletter;
use Guestcms\Newsletter\NewsletterManager;
use Guestcms\Newsletter\Repositories\Eloquent\NewsletterRepository;
use Guestcms\Newsletter\Repositories\Interfaces\NewsletterInterface;
use Guestcms\Setting\PanelSections\SettingOthersPanelSection;
use Guestcms\Theme\FormFrontManager;
use Illuminate\Contracts\Support\DeferrableProvider;

class NewsletterServiceProvider extends ServiceProvider implements DeferrableProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        $this->app->singleton(NewsletterInterface::class, function () {
            return new NewsletterRepository(new Newsletter());
        });

        $this->app->singleton(Factory::class, function ($app) {
            return new NewsletterManager($app);
        });
    }

    public function boot(): void
    {
        $this
            ->setNamespace('plugins/newsletter')
            ->loadHelpers()
            ->loadAndPublishConfigurations(['permissions', 'email'])
            ->loadAndPublishTranslations()
            ->loadRoutes()
            ->publishAssets()
            ->loadAndPublishViews()
            ->loadMigrations();

        $this->app->register(EventServiceProvider::class);

        DashboardMenu::default()->beforeRetrieving(function (): void {
            DashboardMenu::make()
                ->registerItem(
                    DashboardMenuItem::make()
                        ->id('cms-plugins-newsletter')
                        ->priority(430)
                        ->name('plugins/newsletter::newsletter.name')
                        ->icon('ti ti-mail')
                        ->route('newsletter.index')
                );
        });

        PanelSectionManager::default()->beforeRendering(function (): void {
            PanelSectionManager::registerItem(
                SettingOthersPanelSection::class,
                fn () => PanelSectionItem::make('newsletter')
                    ->setTitle(trans('plugins/newsletter::newsletter.settings.title'))
                    ->withIcon('ti ti-mail-cog')
                    ->withDescription(trans('plugins/newsletter::newsletter.settings.panel_description'))
                    ->withPriority(140)
                    ->withRoute('newsletter.settings')
            );
        });

        $this->app->booted(function (): void {
            EmailHandler::addTemplateSettings(NEWSLETTER_MODULE_SCREEN_NAME, config('plugins.newsletter.email', []));
        });

        FormFrontManager::register(NewsletterForm::class, NewsletterRequest::class);
    }

    public function provides(): array
    {
        return [Factory::class];
    }
}
