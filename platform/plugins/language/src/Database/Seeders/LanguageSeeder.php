<?php

namespace Guestcms\Language\Database\Seeders;

use Guestcms\Base\Supports\BaseSeeder;
use Guestcms\Language\Models\Language;
use Guestcms\Language\Models\LanguageMeta;
use Guestcms\LanguageAdvanced\Supports\LanguageAdvancedManager;
use Guestcms\Setting\Facades\Setting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class LanguageSeeder extends BaseSeeder
{
    public function run(): void
    {
        Language::query()->truncate();
        LanguageMeta::query()->truncate();

        if (is_plugin_active('language-advanced')) {
            foreach (LanguageAdvancedManager::supportedModels() as $model) {
                $table = (new $model())->getModel()->getTable() . '_translations';

                if (! Schema::hasTable($table)) {
                    continue;
                }

                DB::table($table)->truncate();
            }
        }

        Language::query()->create([
            'lang_name' => 'English',
            'lang_locale' => 'en',
            'lang_is_default' => true,
            'lang_code' => 'en_US',
            'lang_is_rtl' => false,
            'lang_flag' => 'us',
            'lang_order' => 0,
        ]);

        Setting::set([
            'language_hide_default' => '1',
            'language_switcher_display' => 'dropdown',
            'language_display' => 'all',
            'language_hide_languages' => '[]',
        ])->save();
    }
}
