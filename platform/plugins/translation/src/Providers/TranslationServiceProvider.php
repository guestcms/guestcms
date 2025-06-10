<?php

namespace Guestcms\Translation\Providers;

use Guestcms\Base\Facades\PanelSectionManager;
use Guestcms\Base\PanelSections\PanelSectionItem;
use Guestcms\Base\Supports\ServiceProvider;
use Guestcms\Base\Traits\LoadAndPublishDataTrait;
use Guestcms\DataSynchronize\PanelSections\ExportPanelSection;
use Guestcms\DataSynchronize\PanelSections\ImportPanelSection;
use Guestcms\Translation\Console\AutoTranslateCoreCommand;
use Guestcms\Translation\Console\AutoTranslateThemeCommand;
use Guestcms\Translation\Console\DownloadLocaleCommand;
use Guestcms\Translation\Console\RemoveLocaleCommand;
use Guestcms\Translation\Console\RemoveUnusedTranslationsCommand;
use Guestcms\Translation\Console\UpdateThemeTranslationCommand;
use Guestcms\Translation\PanelSections\LocalizationPanelSection;

class TranslationServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function boot(): void
    {
        $this
            ->setNamespace('plugins/translation')
            ->loadAndPublishConfigurations(['general', 'permissions'])
            ->loadMigrations()
            ->loadRoutes()
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->publishAssets();

        PanelSectionManager::beforeRendering(function (): void {
            PanelSectionManager::register(LocalizationPanelSection::class);
        });

        PanelSectionManager::setGroupId('data-synchronize')->beforeRendering(function (): void {
            PanelSectionManager::default()
                ->registerItem(
                    ExportPanelSection::class,
                    fn () => PanelSectionItem::make('export-theme-translations')
                        ->setTitle(trans('plugins/translation::translation.panel.theme-translations.title'))
                        ->withDescription(trans(
                            'plugins/translation::translation.export_description',
                            ['name' => trans('plugins/translation::translation.panel.theme-translations.title')]
                        ))
                        ->withPriority(999)
                        ->withPermission('theme-translations.export')
                        ->withRoute('tools.data-synchronize.export.theme-translations.index')
                )
                ->registerItem(
                    ExportPanelSection::class,
                    fn () => PanelSectionItem::make('other-translations')
                        ->setTitle(trans('plugins/translation::translation.panel.admin-translations.title'))
                        ->withDescription(trans(
                            'plugins/translation::translation.export_description',
                            ['name' => trans('plugins/translation::translation.panel.admin-translations.title')]
                        ))
                        ->withPriority(999)
                        ->withPermission('other-translations.export')
                        ->withRoute('tools.data-synchronize.export.other-translations.index')
                )
                ->registerItem(
                    ImportPanelSection::class,
                    fn () => PanelSectionItem::make('import-theme-translations')
                        ->setTitle(trans('plugins/translation::translation.panel.theme-translations.title'))
                        ->withDescription(trans(
                            'plugins/translation::translation.import_description',
                            ['name' => trans('plugins/translation::translation.panel.theme-translations.title')]
                        ))
                        ->withPriority(999)
                        ->withPermission('theme-translations.import')
                        ->withRoute('tools.data-synchronize.import.theme-translations.index')
                )
                ->registerItem(
                    ImportPanelSection::class,
                    fn () => PanelSectionItem::make('other-translations')
                        ->setTitle(trans('plugins/translation::translation.panel.admin-translations.title'))
                        ->withDescription(trans(
                            'plugins/translation::translation.import_description',
                            ['name' => trans('plugins/translation::translation.panel.admin-translations.title')]
                        ))
                        ->withPriority(999)
                        ->withPermission('other-translations.import')
                        ->withRoute('tools.data-synchronize.import.other-translations.index')
                );
        });

        if ($this->app->runningInConsole()) {
            $this->commands([
                UpdateThemeTranslationCommand::class,
                RemoveUnusedTranslationsCommand::class,
                DownloadLocaleCommand::class,
                RemoveLocaleCommand::class,
                AutoTranslateThemeCommand::class,
                AutoTranslateCoreCommand::class,
            ]);
        }
    }
}
