<?php

namespace Guestcms\Installer\Http\Middleware;

use Guestcms\Base\Facades\BaseHelper;
use Carbon\Carbon;
use Closure;
use Exception;
use Illuminate\Http\Request;

class CheckIfInstallingMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        try {
            $content = BaseHelper::getFileData(storage_path(INSTALLING_SESSION_NAME));

            $startingDate = Carbon::parse($content);

            if (! $content || Carbon::now()->diffInMinutes($startingDate) > 30) {
                return redirect()->to('/');
            }
        } catch (Exception) {
            return redirect()->to('/');
        }

        return $next($request);
    }
}
