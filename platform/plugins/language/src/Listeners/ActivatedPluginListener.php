<?php

namespace Guestcms\Language\Listeners;

use Guestcms\Base\Facades\BaseHelper;
use Guestcms\Language\Plugin;
use Exception;

class ActivatedPluginListener
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
