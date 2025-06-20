<?php

use Guestcms\Setting\Models\Setting as SettingModel;
use Guestcms\Theme\Facades\Theme;
use Illuminate\Database\Migrations\Migration;

return new class () extends Migration {
    public function up(): void
    {
        $theme = Theme::getThemeName();

        foreach (SettingModel::query()->get() as $item) {
            $item->key = str_replace('theme--', 'theme-' . $theme . '-', $item->key);
            $item->save();
        }
    }
};
