<?php

namespace Guestcms\Base\Http\Controllers;

use Guestcms\ACL\Models\UserMeta;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ToggleThemeModeController extends BaseController
{
    public function __invoke(Request $request): RedirectResponse
    {
        $request->validate(['theme' => 'required|in:light,dark']);

        $themeMode = $request->query('theme');

        UserMeta::setMeta('theme_mode', $themeMode);

        return redirect()->back();
    }
}
