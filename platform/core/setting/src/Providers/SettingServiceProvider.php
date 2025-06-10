<?php

namespace Guestcms\Setting\Providers;

use Guestcms\Base\Events\PanelSectionsRendering;
use Guestcms\Base\Facades\DashboardMenu;
use Guestcms\Base\Facades\EmailHandler;
use Guestcms\Base\Facades\PanelSectionManager;
use Guestcms\Base\PanelSections\PanelSectionItem;
use Guestcms\Base\PanelSections\System\SystemPanelSection;
use Guestcms\Base\Supports\DashboardMenuItem;
use Guestcms\Base\Supports\ServiceProvider;
use Guestcms\Base\Traits\LoadAndPublishDataTrait;
use Guestcms\Setting\Commands\CronJobTestCommand;
use Guestcms\Setting\Facades\Setting;
use Guestcms\Setting\Listeners\PushDashboardMenuToOtherSectionPanel;
use Guestcms\Setting\Models\Setting as SettingModel;
use Guestcms\Setting\PanelSections\SettingCommonPanelSection;
use Guestcms\Setting\PanelSections\SettingOthersPanelSection;
use Guestcms\Setting\Repositories\Eloquent\SettingRepository;
use Guestcms\Setting\Repositories\Interfaces\SettingInterface;
use Guestcms\Setting\Supports\DatabaseSettingStore;
use Guestcms\Setting\Supports\SettingStore;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\AliasLoader;

class SettingServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    protected bool $defer = true;

    public function register(): void
    {
        $this
            ->setNamespace('core/setting')
            ->loadAndPublishConfigurations(['general']);

        $this->app->singleton(SettingStore::class, function () {
            return new DatabaseSettingStore();
        });

        $this->app->bind(SettingInterface::class, function () {
            return new SettingRepository(new SettingModel());
        });

        if (! class_exists('Setting')) {
            AliasLoader::getInstance()->alias('Setting', Setting::class);
        }

        $this->loadHelpers();
    }

    public function boot(): void
    {
        $this
            ->loadRoutes()
            ->loadAndPublishViews()
            ->loadAnonymousComponents()
            ->loadAndPublishTranslations()
            ->loadAndPublishConfigurations(['permissions', 'email'])
            ->loadMigrations()
            ->publishAssets();

        DashboardMenu::default()->beforeRetrieving(function (): void {
            DashboardMenu::make()
                ->registerItem(
                    DashboardMenuItem::make()
                        ->id('cms-core-settings')
                        ->priority(9999)
                        ->name('core/setting::setting.title')
                        ->icon('ti ti-settings')
                        ->route('settings.index')
                        ->permission('settings.index')
                );
        });

        $events = $this->app['events'];

        $this->app->booted(function (): void {
            EmailHandler::addTemplateSettings('base', config('core.setting.email', []), 'core');
        });

        PanelSectionManager::default()
            ->beforeRendering(function (): void {
                PanelSectionManager::setGroupName(trans('core/setting::setting.title'))
                    ->register([
                        SettingCommonPanelSection::class,
                        SettingOthersPanelSection::class,
                    ]);
            });

        PanelSectionManager::group('system')->beforeRendering(function (): void {
            PanelSectionManager::registerItem(
                SystemPanelSection::class,
                fn () => PanelSectionItem::make('cronjob')
                    ->setTitle(trans('core/setting::setting.cronjob.name'))
                    ->withIcon('ti ti-calendar-event')
                    ->withDescription(trans('core/setting::setting.cronjob.description'))
                    ->withPriority(50)
                    ->withRoute('system.cronjob')
            );
        });

        $events->listen(PanelSectionsRendering::class, PushDashboardMenuToOtherSectionPanel::class);

        if ($this->app->runningInConsole()) {
            $this->commands([
                CronJobTestCommand::class,
            ]);

            $this->app->afterResolving(Schedule::class, function (Schedule $schedule): void {
                rescue(function () use ($schedule): void {
                    $schedule
                        ->command(CronJobTestCommand::class)
                        ->everyMinute();
                });
            });
        }
    }

    public function provides(): array
    {
        return [
            SettingStore::class,
        ];
    }
}
