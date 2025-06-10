<?php

namespace Database\Seeders;

use Guestcms\Base\Supports\BaseSeeder;
use Guestcms\Setting\Facades\Setting;
use Guestcms\Theme\Facades\ThemeOption;

class ThemeOptionSeeder extends BaseSeeder
{
    public function run(): void
    {
        $this->uploadFiles('general');
        $this->uploadFiles('sliders');

        Setting::newQuery()->where('key', 'LIKE', ThemeOption::getOptionKey('%'))->delete();

        Setting::set(ThemeOption::prepareFromArray([
            'site_title' => 'Mystic Sea Motel',
            'seo_description' => 'Mystic Sea Motel',
            'copyright' => '©%Y Mystic Sea Motel. All right reserved.',
            'cookie_consent_message' => 'Your experience on this site will be improved by allowing cookies ',
            'cookie_consent_learn_more_url' => '/cookie-policy',
            'cookie_consent_learn_more_text' => 'Cookie Policy',
            'homepage_id' => '1',
            'blog_page_id' => '2',
            'logo' => 'general/logo.png',
            'logo_white' => 'general/logo-white.png',
            'favicon' => 'general/favicon.png',
            'email' => 'info@webmail.com',
            'address' => '2105 S Ocean Blvd,Myrtle Beach SC 29577',
            'hotline' => '843-448-8446',
            'news_banner' => 'general/banner-news.jpg',
            'rooms_banner' => 'general/banner-news.jpg',
            'term_of_use_url' => '#',
            'privacy_policy_url' => '#',
            'preloader_enabled' => 'no',
            'about-us' => 'Mystic Sea Motel is a family-owned and operated mom-and-pop motel located just across the street from the beach in the heart of Myrtle Beach.',
            'hotel_rules' => '<ul><li>No smoking, parties or events.</li><li>Check-in time from 2 PM, check-out by 10 AM.</li><li>Time to time car parking</li><li>Download Our minimal app</li><li>Browse regular our website</li></ul>',
            'cancellation' => '<p> <strong>Cancel up</strong> to <strong>14 days</strong> to get a full refund.</p>',
            'slider-image-1' => 'sliders/04.jpg',
            'slider-title-1' => 'Your place by the sea run by family',
            'slider-description-1' => '<p>The Perfect<br>Place For You</p>',
            'slider-primary-button-text-1' => 'View Rooms',
            'slider-primary-button-url-1' => '/rooms',
            'slider-secondary-button-text-1' => 'About us',
            'slider-secondary-button-url-1' => '/about-us',
            'slider-image-2' => 'sliders/05.jpg',
            'slider-title-2' => 'Your place by the sea run by family',
            'slider-description-2' => '<p>The Perfect<br>Place For You</p>',
            'slider-primary-button-text-2' => 'View Rooms',
            'slider-primary-button-url-2' => '/rooms',
            'slider-secondary-button-text-2' => 'About us',
            'slider-secondary-button-url-2' => '/about-us',
            'primary_font' => 'Archivo',
            'secondary_font' => 'Old Standard TT',
            'tertiary_font' => 'Roboto',
            'social_links' => [
                [
                    [
                        'key' => 'social-name',
                        'value' => 'Facebook',
                    ],
                    [
                        'key' => 'social-icon',
                        'value' => 'fab fa-facebook-f',
                    ],
                    [
                        'key' => 'social-url',
                        'value' => 'https://www.facebook.com/',
                    ],
                ],
                [
                    [
                        'key' => 'social-name',
                        'value' => 'Twitter',
                    ],
                    [
                        'key' => 'social-icon',
                        'value' => 'fab fa-twitter',
                    ],
                    [
                        'key' => 'social-url',
                        'value' => 'https://www.twitter.com/',
                    ],
                ],
                [
                    [
                        'key' => 'social-name',
                        'value' => 'Youtube',
                    ],
                    [
                        'key' => 'social-icon',
                        'value' => 'fab fa-youtube',
                    ],
                    [
                        'key' => 'social-url',
                        'value' => 'https://www.youtube.com/',
                    ],
                ],
                [
                    [
                        'key' => 'social-name',
                        'value' => 'Linkedin',
                    ],
                    [
                        'key' => 'social-icon',
                        'value' => 'fab fa-linkedin',
                    ],
                    [
                        'key' => 'social-url',
                        'value' => 'https://www.linkedin.com/',
                    ],
                ],
            ],
        ]));

        Setting::save();
    }
}
