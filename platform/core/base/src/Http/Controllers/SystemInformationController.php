<?php

namespace Guestcms\Base\Http\Controllers;

use Guestcms\Base\Facades\Assets;
use Guestcms\Base\Facades\BaseHelper;
use Guestcms\Base\Supports\SystemManagement;
use Guestcms\Base\Tables\InfoTable;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class SystemInformationController extends BaseSystemController
{
    public function index(Request $request, InfoTable $infoTable)
    {
        $this->pageTitle(trans('core/base::system.info.title'));

        Assets::addScriptsDirectly('vendor/core/core/base/js/system-info.js');

        $composerArray = SystemManagement::getComposerArray();
        $packages = SystemManagement::getPackagesAndDependencies($composerArray['require']);

        if ($request->expectsJson()) {
            return $infoTable->renderTable();
        }

        $systemEnv = SystemManagement::getSystemEnv();
        $serverEnv = SystemManagement::getServerEnv();

        $requiredPhpVersion = Arr::get($composerArray, 'require.php', get_minimum_php_version());
        $requiredPhpVersion = str_replace('^', '', $requiredPhpVersion);
        $requiredPhpVersion = str_replace('~', '', $requiredPhpVersion);

        $matchPHPRequirement = version_compare(phpversion(), $requiredPhpVersion, '>=') > 0;

        return view(
            'core/base::system.info',
            compact(
                'packages',
                'infoTable',
                'systemEnv',
                'serverEnv',
                'matchPHPRequirement',
                'requiredPhpVersion',
            )
        );
    }

    public function getAdditionData()
    {
        BaseHelper::maximumExecutionTimeAndMemoryLimit();

        $appSize = BaseHelper::humanFilesize(SystemManagement::getAppSize());

        return $this
            ->httpResponse()
            ->setData(compact('appSize'));
    }
}
