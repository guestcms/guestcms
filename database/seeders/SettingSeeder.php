<?php

namespace Database\Seeders;

use Guestcms\Base\Supports\BaseSeeder;
use Guestcms\Blog\Models\Category;
use Guestcms\Blog\Models\Post;
use Guestcms\Setting\Facades\Setting;
use Guestcms\Slug\Facades\SlugHelper;
use Guestcms\Slug\Models\Slug;
use Guestcms\Theme\Facades\Theme;

class SettingSeeder extends BaseSeeder
{
    public function run(): void
    {
        $settings = [
            'show_admin_bar' => '1',
            'theme' => Theme::getThemeName(),
            'media_random_hash' => md5(time()),
            'admin_favicon' => 'general/favicon.png',
            'admin_logo' => 'general/logo-white.png',
            SlugHelper::getPermalinkSettingKey(Post::class) => 'news',
            SlugHelper::getPermalinkSettingKey(Category::class) => 'news',

            'payment_cod_status' => 1,
            'payment_cod_description' => 'Please pay money directly to the postman, if you choose cash on delivery method (COD).',
            'payment_bank_transfer_status' => 1,
            'payment_bank_transfer_description' => 'Please send money to our bank account: ACB - 69270 213 19.',
            'payment_stripe_payment_type' => 'stripe_checkout',

            'language_switcher_display' => 'dropdown',
        ];

        Setting::delete(array_keys($settings));

        Setting::set($settings)->save();

        Slug::query()->where('reference_type', Post::class)->update(['prefix' => 'news']);
        Slug::query()->where('reference_type', Category::class)->update(['prefix' => 'news']);
    }
}
