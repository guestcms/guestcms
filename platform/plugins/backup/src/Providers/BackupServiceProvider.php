<?php

namespace Guestcms\Backup\Providers;

use Guestcms\Base\Facades\PanelSectionManager;
use Guestcms\Base\PanelSections\PanelSectionItem;
use Guestcms\Base\PanelSections\System\SystemPanelSection;
use Guestcms\Base\Supports\ServiceProvider;
use Guestcms\Base\Traits\LoadAndPublishDataTrait;

class BackupServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function boot(): void
    {
        $this->setNamespace('plugins/backup')
            ->loadHelpers()
            ->loadAndPublishConfigurations(['permissions', 'general'])
            ->loadRoutes()
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->publishAssets();

        $this->app->register(CommandServiceProvider::class);

        PanelSectionManager::group('system')->beforeRendering(function (): void {
            PanelSectionManager::registerItem(
                SystemPanelSection::class,
                fn () => PanelSectionItem::make('backup')
                    ->setTitle(trans('plugins/backup::backup.name'))
                    ->withIcon('ti ti-database-share')
                    ->withDescription(trans('plugins/backup::backup.backup_description'))
                    ->withPriority(30)
                    ->withRoute('backups.index')
            );
        });

        $this->app->booted(function (): void {
            $this->app->register(HookServiceProvider::class);
        });
    }
}
