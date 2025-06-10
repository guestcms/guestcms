<?php

namespace Guestcms\Base\Providers;

use Guestcms\ACL\Models\User;
use Guestcms\Base\Supports\ServiceProvider;
use Guestcms\Media\Facades\RvMedia;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Facades\Auth;

class ComposerServiceProvider extends ServiceProvider
{
    public function boot(Factory $view): void
    {
        $view->composer(['core/media::config'], function (): void {
            $mediaPermissions = RvMedia::getConfig('permissions', []);

            if (Auth::guard()->check()) {
                /**
                 * @var User $user
                 */
                $user = Auth::guard()->user();

                if (! $user->isSuperUser() && $user->permissions) {
                    $mediaPermissions = array_intersect(array_keys($user->permissions), $mediaPermissions);
                }
            }

            RvMedia::setPermissions($mediaPermissions);
        });
    }
}
