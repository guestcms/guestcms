<?php

namespace Guestcms\LanguageAdvanced\Listeners;

use Guestcms\Base\Facades\BaseHelper;
use Guestcms\LanguageAdvanced\Plugin;
use Exception;

class PriorityLanguageAdvancedPluginListener
{
    public function handle(): void
    {
        try {
            Plugin::activated();
        } catch (Exception $exception) {
            BaseHelper::logError($exception);
        }
    }
}
