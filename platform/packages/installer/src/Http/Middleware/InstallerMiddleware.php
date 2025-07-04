<?php

namespace Guestcms\Installer\Http\Middleware;

use Guestcms\Base\Supports\Helper;
use Illuminate\Support\Facades\File;

abstract class InstallerMiddleware
{
    public function alreadyInstalled(): bool
    {
        if (! config('packages.installer.installer.enabled')) {
            return true;
        }

        if (File::exists(storage_path('installed'))) {
            return true;
        }

        return ! File::exists(storage_path('installing')) && Helper::isConnectedDatabase();
    }
}
