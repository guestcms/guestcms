<?php

namespace Guestcms\Setting\Forms;

use Guestcms\Base\Facades\AdminAppearance;
use Guestcms\Base\Facades\BaseHelper;
use Guestcms\Base\Forms\FieldOptions\CodeEditorFieldOption;
use Guestcms\Base\Forms\FieldOptions\NumberFieldOption;
use Guestcms\Base\Forms\FieldOptions\SelectFieldOption;
use Guestcms\Base\Forms\Fields\CodeEditorField;
use Guestcms\Base\Forms\Fields\ColorField;
use Guestcms\Base\Forms\Fields\GoogleFontsField;
use Guestcms\Base\Forms\Fields\MediaImageField;
use Guestcms\Base\Forms\Fields\MediaImagesField;
use Guestcms\Base\Forms\Fields\NumberField;
use Guestcms\Base\Forms\Fields\OnOffCheckboxField;
use Guestcms\Base\Forms\Fields\RadioField;
use Guestcms\Base\Forms\Fields\SelectField;
use Guestcms\Base\Forms\Fields\TextField;
use Guestcms\Base\Forms\FormAbstract;
use Guestcms\Base\Supports\Language;
use Guestcms\Setting\Http\Requests\AdminAppearanceRequest;

class AdminAppearanceSettingForm extends SettingForm
{
    public function setup(): void
    {
        parent::setup();

        $this
            ->setSectionTitle(trans('core/setting::setting.admin_appearance.title'))
            ->setSectionDescription(trans('core/setting::setting.admin_appearance.description'))
            ->setValidatorClass(AdminAppearanceRequest::class)
            ->add('admin_logo', MediaImageField::class, [
                'label' => trans('core/setting::setting.admin_appearance.form.admin_logo'),
                'value' => setting('admin_logo'),
            ])
            ->add(
                'admin_logo_max_height',
                NumberField::class,
                NumberFieldOption::make()
                    ->label(trans('core/setting::setting.admin_appearance.form.admin_logo_max_height'))
                    ->helperText(trans('core/setting::setting.admin_appearance.form.admin_logo_max_height_helper', ['default' => '32px']))
                    ->value(setting('admin_logo_max_height', 32))
                    ->min(10)
                    ->max(300)
            )
            ->add('admin_favicon', MediaImageField::class, [
                'label' => trans('core/setting::setting.admin_appearance.form.admin_favicon'),
                'value' => setting('admin_favicon'),
            ])
            ->add('login_screen_backgrounds[]', MediaImagesField::class, [
                'label' => trans('core/setting::setting.admin_appearance.form.admin_login_screen_backgrounds'),
                'value' => is_array(setting('login_screen_backgrounds', ''))
                    ? setting('login_screen_backgrounds', '')
                    : json_decode(setting('login_screen_backgrounds', ''), true),
                'values' => setting('login_screen_backgrounds', []),
            ])
            ->add('admin_title', TextField::class, [
                'label' => trans('core/setting::setting.admin_appearance.form.admin_title'),
                'value' => setting('admin_title', config('app.name')),
                'attr' => [
                    'data-counter' => 120,
                ],
            ])
            ->add('admin_primary_font', GoogleFontsField::class, [
                'label' => trans('core/setting::setting.admin_appearance.form.primary_font'),
                'selected' => setting('admin_primary_font', 'Inter'),
            ])
            ->add('admin_primary_color', ColorField::class, [
                'label' => trans('core/setting::setting.admin_appearance.form.primary_color'),
                'value' => setting('admin_primary_color', '#206bc4'),
            ])
            ->add('admin_secondary_color', ColorField::class, [
                'label' => trans('core/setting::setting.admin_appearance.form.secondary_color'),
                'value' => setting('admin_secondary_color', '#6c7a91'),
            ])
            ->add('admin_heading_color', ColorField::class, [
                'label' => trans('core/setting::setting.admin_appearance.form.heading_color'),
                'value' => setting('admin_heading_color', 'inherit'),
            ])
            ->add('admin_text_color', ColorField::class, [
                'label' => trans('core/setting::setting.admin_appearance.form.text_color'),
                'value' => setting('admin_text_color', '#182433'),
            ])
            ->add('admin_link_color', ColorField::class, [
                'label' => trans('core/setting::setting.admin_appearance.form.link_color'),
                'value' => setting('admin_link_color', '#206bc4'),
            ])
            ->add('admin_link_hover_color', ColorField::class, [
                'label' => trans('core/setting::setting.admin_appearance.form.link_hover_color'),
                'value' => setting('admin_link_hover_color', '#1a569d'),
            ])
            ->when(! empty($locales = Language::getAvailableLocales()), function (FormAbstract $form) use ($locales): void {
                $form->add(
                    AdminAppearance::getSettingKey('locale'),
                    SelectField::class,
                    SelectFieldOption::make()
                        ->label(trans('core/setting::setting.admin_appearance.form.admin_locale'))
                        ->choices(collect($locales)
                            ->pluck('name', 'locale')
                            ->map(fn ($item, $key) => $item . ' - ' . $key)
                            ->all())
                        ->selected(AdminAppearance::getSetting('locale', config('core.base.general.locale', config('app.locale'))))
                        ->searchable()
                );
            })
            ->add(AdminAppearance::getSettingKey('locale_direction'), RadioField::class, [
                'label' => trans('core/setting::setting.admin_appearance.form.admin_locale_direction'),
                'value' => AdminAppearance::getSetting('locale_direction', setting('admin_locale_direction', 'ltr')),
                'values' => [
                    'ltr' => trans('core/setting::setting.locale_direction_ltr'),
                    'rtl' => trans('core/setting::setting.locale_direction_rtl'),
                ],
            ])
            ->add('rich_editor', RadioField::class, [
                'label' => trans('core/setting::setting.admin_appearance.form.rich_editor'),
                'value' => BaseHelper::getRichEditor(),
                'values' => BaseHelper::availableRichEditors(),
            ])
            ->add(AdminAppearance::getSettingKey('layout'), RadioField::class, [
                'label' => trans('core/setting::setting.admin_appearance.layout'),
                'value' => AdminAppearance::getCurrentLayout(),
                'values' => AdminAppearance::getLayouts(),
            ])
            ->add(AdminAppearance::getSettingKey('container_width'), RadioField::class, [
                'label' => trans('core/setting::setting.admin_appearance.container_width.title'),
                'value' => AdminAppearance::getContainerWidth(),
                'values' => AdminAppearance::getContainerWidths(),
            ])
            ->add(AdminAppearance::getSettingKey('show_menu_item_icon'), OnOffCheckboxField::class, [
                'label' => trans('core/setting::setting.admin_appearance.form.show_menu_item_icon'),
                'value' => AdminAppearance::showMenuItemIcon(),
            ])
            ->add(
                AdminAppearance::getSettingKey('custom_css'),
                CodeEditorField::class,
                CodeEditorFieldOption::make()
                    ->label(trans('core/setting::setting.admin_appearance.form.custom_css'))
                    ->value(AdminAppearance::getSetting('custom_css'))
                    ->mode('css')
            )
            ->add(
                AdminAppearance::getSettingKey('custom_header_js'),
                CodeEditorField::class,
                CodeEditorFieldOption::make()
                    ->label(trans('core/setting::setting.admin_appearance.form.custom_header_js'))
                    ->helperText(trans('core/setting::setting.admin_appearance.form.custom_header_js_placeholder'))
                    ->value(AdminAppearance::getSetting('custom_header_js'))
                    ->mode('javascript')
                    ->maxLength(2500)
            )
            ->add(
                AdminAppearance::getSettingKey('custom_body_js'),
                CodeEditorField::class,
                CodeEditorFieldOption::make()
                    ->label(trans('core/setting::setting.admin_appearance.form.custom_body_js'))
                    ->helperText(trans('core/setting::setting.admin_appearance.form.custom_body_js_placeholder'))
                    ->value(AdminAppearance::getSetting('custom_body_js'))
                    ->mode('javascript')
                    ->maxLength(2500)
            )
            ->add(
                AdminAppearance::getSettingKey('custom_footer_js'),
                CodeEditorField::class,
                CodeEditorFieldOption::make()
                    ->label(trans('core/setting::setting.admin_appearance.form.custom_footer_js'))
                    ->helperText(trans('core/setting::setting.admin_appearance.form.custom_footer_js_placeholder'))
                    ->value(AdminAppearance::getSetting('custom_footer_js'))
                    ->mode('javascript')
                    ->maxLength(2500)
            );
    }
}
