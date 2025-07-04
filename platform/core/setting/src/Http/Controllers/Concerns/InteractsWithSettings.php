<?php

namespace Guestcms\Setting\Http\Controllers\Concerns;

use Guestcms\Base\Facades\DashboardMenu;
use Guestcms\Base\Http\Responses\BaseHttpResponse;
use Guestcms\Setting\Facades\Setting;
use Illuminate\Support\Arr;

trait InteractsWithSettings
{
    protected bool $forceSaveSettings = false;

    protected function saveSettings(array $data, string $prefix = ''): void
    {
        foreach (Arr::except($data, ['_token', '_method']) as $settingKey => $settingValue) {
            if (is_array($settingValue)) {
                $settingValue = json_encode(array_filter($settingValue));
            }

            Setting::set($prefix . $settingKey, (string) $settingValue, $this->forceSaveSettings);
        }

        Setting::save();
    }

    protected function performUpdate(array $data, string $prefix = ''): BaseHttpResponse
    {
        do_action('core_before_update_settings', $data, $prefix);

        $this->saveSettings($data, $prefix);

        if (! method_exists($this, 'httpResponse')) {
            return BaseHttpResponse::make();
        }

        DashboardMenu::clearCaches();

        do_action('core_after_update_settings', $data, $prefix);

        return $this
            ->httpResponse()
            ->withUpdatedSuccessMessage();
    }
}
