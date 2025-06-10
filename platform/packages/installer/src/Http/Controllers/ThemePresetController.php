<?php

namespace Guestcms\Installer\Http\Controllers;

use Guestcms\Base\Http\Controllers\BaseController;
use Guestcms\Installer\Http\Controllers\Concerns\InteractsWithDatabaseFile;
use Guestcms\Installer\Http\Requests\ChooseThemePresetRequest;
use Guestcms\Installer\InstallerStep\InstallerStep;
use Guestcms\Installer\Services\ImportDatabaseService;
use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class ThemePresetController extends BaseController
{
    use InteractsWithDatabaseFile;

    public function __construct()
    {
        $this->middleware(function (Request $request, Closure $next) {
            abort_if(! InstallerStep::hasMoreThemePresets(), 404);

            return $next($request);
        });
    }

    public function index(Request $request): View|RedirectResponse
    {
        if (! URL::hasValidSignature($request)) {
            return redirect()->route('installers.requirements.index');
        }

        $themePresets = InstallerStep::getThemePresets();

        return view('packages/installer::theme-preset', compact('themePresets'));
    }

    public function store(ChooseThemePresetRequest $request, ImportDatabaseService $importDatabaseService): RedirectResponse
    {
        $this->handleImportDatabaseFile($importDatabaseService, $request->input('theme_preset'));

        return redirect()
            ->to(URL::temporarySignedRoute('installers.accounts.index', Carbon::now()->addMinutes(30)));
    }
}
