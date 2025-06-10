<?php

namespace Guestcms\Language\Listeners;

use Guestcms\Base\Events\CreatedContentEvent;
use Guestcms\Language\Listeners\Concerns\EnsureThemePackageExists;
use Guestcms\Language\Models\Language;
use Guestcms\Widget\Models\Widget;

class CopyThemeWidgets
{
    use EnsureThemePackageExists;

    public function handle(CreatedContentEvent $event): void
    {
        if (! $this->determineIfThemesExists()) {
            return;
        }

        if (! $event->data instanceof Language) {
            return;
        }

        $theme = setting('theme');

        if (! $theme) {
            return;
        }

        $copiedWidgets = Widget::query()
            ->where('theme', $theme)
            ->get()
            ->toArray();

        if (empty($copiedWidgets)) {
            return;
        }

        foreach ($copiedWidgets as $key => $widget) {
            $copiedWidgets[$key]['theme'] = $theme . '-' . $event->data->lang_code;
            $copiedWidgets[$key]['data'] = json_encode($widget['data']);
            unset($copiedWidgets[$key]['id']);
        }

        Widget::query()->insertOrIgnore($copiedWidgets);
    }
}
