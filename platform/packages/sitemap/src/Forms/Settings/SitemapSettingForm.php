<?php

namespace Guestcms\Sitemap\Forms\Settings;

use Guestcms\Base\Forms\FieldOptions\CheckboxFieldOption;
use Guestcms\Base\Forms\FieldOptions\NumberFieldOption;
use Guestcms\Base\Forms\Fields\NumberField;
use Guestcms\Base\Forms\Fields\OnOffCheckboxField;
use Guestcms\Setting\Forms\SettingForm;
use Guestcms\Sitemap\Http\Requests\SitemapSettingRequest;

class SitemapSettingForm extends SettingForm
{
    public function setup(): void
    {
        parent::setup();

        $this
            ->setSectionTitle(trans('packages/sitemap::sitemap.settings.title'))
            ->setSectionDescription(trans('packages/sitemap::sitemap.settings.description'))
            ->setValidatorClass(SitemapSettingRequest::class)
            ->add(
                'sitemap_enabled',
                OnOffCheckboxField::class,
                CheckboxFieldOption::make()
                    ->label(trans('packages/sitemap::sitemap.settings.enable_sitemap'))
                    ->value($sitemapEnabled = setting('sitemap_enabled', true))
                    ->helperText(trans('packages/sitemap::sitemap.settings.enable_sitemap_help'))
            )
            ->addOpenCollapsible('sitemap_enabled', '1', $sitemapEnabled)
            ->add(
                'sitemap_items_per_page',
                NumberField::class,
                NumberFieldOption::make()
                    ->label(trans('packages/sitemap::sitemap.settings.sitemap_items_per_page'))
                    ->value(setting('sitemap_items_per_page', 1000))
                    ->helperText(trans('packages/sitemap::sitemap.settings.sitemap_items_per_page_help'))
                    ->min(10)
                    ->max(100000)
            )
            ->addCloseCollapsible('sitemap_enabled', '1');
    }
}
