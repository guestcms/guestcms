<?php

use Guestcms\Base\Facades\MetaBox;
use Guestcms\Hotel\Models\Amenity;
use Guestcms\Hotel\Models\Feature;
use Guestcms\Hotel\Models\FoodType;
use Guestcms\Media\Facades\RvMedia;
use Guestcms\Menu\Facades\Menu;
use Guestcms\Theme\Supports\ThemeSupport;

register_page_template([
    'no-sidebar' => __('No Sidebar'),
    'full-width' => __('Full width'),
    'homepage' => __('Homepage'),
]);

register_sidebar([
    'id' => 'footer_sidebar',
    'name' => __('Footer sidebar'),
    'description' => __('Sidebar in the footer of site'),
]);

app()->booted(function (): void {
    ThemeSupport::registerSiteCopyright();
    ThemeSupport::registerSiteLogoHeight(100);

    Menu::removeMenuLocation('main-menu')
        ->addMenuLocation('header-menu', __('Header Navigation'))
        ->addMenuLocation('side-menu', __('Side Navigation'));

    RvMedia::addSize('380x280', 380, 280)
        ->addSize('380x575', 380, 575)
        ->addSize('775x280', 775, 280)
        ->addSize('770x460', 770, 460)
        ->addSize('550x580', 550, 580)
        ->addSize('1170x570', 1170, 570);

    if (is_plugin_active('hotel')) {
        add_filter(BASE_FILTER_BEFORE_RENDER_FORM, function ($form, $data) {
            if (in_array(get_class($data), [Amenity::class, Feature::class, FoodType::class])) {
                $iconImage = null;

                if ($data->id) {
                    $iconImage = MetaBox::getMetaData($data, 'icon_image', true);
                }

                $form
                    ->modify('icon', 'themeIcon', ['label' => __('Font Icon')], true)
                    ->addAfter('icon', 'icon_image', 'mediaImage', [
                        'value' => $iconImage,
                        'label' => __('Icon Image (It will replace Font Icon if it is present)'),
                    ]);
            }

            return $form;
        }, 127, 2);

        add_action(
            [BASE_ACTION_AFTER_CREATE_CONTENT, BASE_ACTION_AFTER_UPDATE_CONTENT],
            function ($type, $request, $object): void {
                if (in_array(get_class($object), [Amenity::class, Feature::class, FoodType::class]) && $request->has(
                    'icon_image'
                )) {
                    MetaBox::saveMetaBoxData($object, 'icon_image', $request->input('icon_image'));
                }
            },
            230,
            3
        );
    }
});
