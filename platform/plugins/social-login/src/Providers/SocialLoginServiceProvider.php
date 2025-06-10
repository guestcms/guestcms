<?php

namespace Guestcms\SocialLogin\Providers;

use Guestcms\Base\Facades\PanelSectionManager;
use Guestcms\Base\PanelSections\PanelSectionItem;
use Guestcms\Base\Supports\ServiceProvider;
use Guestcms\Base\Traits\LoadAndPublishDataTrait;
use Guestcms\Setting\PanelSections\SettingOthersPanelSection;
use Guestcms\SocialLogin\Console\RefreshSocialTokensCommand;
use Guestcms\SocialLogin\Facades\SocialService;
use Guestcms\SocialLogin\Services\SocialLoginService;
use Guestcms\SocialLogin\Supports\SocialService as SocialServiceSupport;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\AliasLoader;

class SocialLoginServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function boot(): void
    {
        $this
            ->setNamespace('plugins/social-login')
            ->loadHelpers()
            ->loadAndPublishConfigurations(['permissions', 'general'])
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->loadMigrations()
            ->loadRoutes()
            ->publishAssets();

        AliasLoader::getInstance()->alias('SocialService', SocialService::class);

        PanelSectionManager::default()->beforeRendering(function (): void {
            PanelSectionManager::registerItem(
                SettingOthersPanelSection::class,
                fn () => PanelSectionItem::make('social-login')
                    ->setTitle(trans('plugins/social-login::social-login.menu'))
                    ->withDescription(trans('plugins/social-login::social-login.description'))
                    ->withIcon('ti ti-social')
                    ->withPriority(100)
                    ->withRoute('social-login.settings')
            );
        });

        $this->app->register(HookServiceProvider::class);

        $this->app->afterResolving(Schedule::class, function (Schedule $schedule): void {
            $schedule->command(RefreshSocialTokensCommand::class)->daily();
        });
    }

    public function register(): void
    {
        $this->app->singleton(SocialServiceSupport::class, function () {
            return new SocialServiceSupport();
        });

        $this->app->singleton(SocialLoginService::class);

        $this->commands([
            RefreshSocialTokensCommand::class,
        ]);
    }
}
