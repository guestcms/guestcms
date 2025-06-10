<?php

namespace Guestcms\Base\Http\Controllers;

use Guestcms\Base\Facades\BaseHelper;
use Guestcms\Setting\Facades\Setting;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Symfony\Component\Process\PhpExecutableFinder;

class CronjobSettingController extends BaseSystemController
{
    public function index(): View
    {
        $this->pageTitle(trans('core/setting::setting.cronjob.name'));

        $command = sprintf(
            '* * * * * %s %s/artisan schedule:run >> /dev/null 2>&1',
            (new PhpExecutableFinder())->find(false),
            BaseHelper::hasDemoModeEnabled() ? 'path-to-your-project' : base_path()
        );

        $lastRunAt = Setting::get('cronjob_last_run_at');

        if ($lastRunAt) {
            $lastRunAt = Carbon::parse($lastRunAt);
        }

        return view('core/setting::cronjob', compact('command', 'lastRunAt'));
    }
}
