<?php

namespace Guestcms\Base\Hooks;

use Guestcms\Base\Facades\EmailHandler;

class EmailSettingHooks
{
    public static function addEmailTemplateSettings(?string $html): string
    {
        $templates = '';

        foreach (EmailHandler::getTemplates() as $type => $item) {
            foreach ($item as $module => $data) {
                $templates .= view('core/setting::template-line', compact('type', 'module', 'data'))->render();
            }
        }

        return $html . $templates;
    }
}
