<?php

namespace Guestcms\ACL\Listeners;

use Guestcms\ACL\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\Login;

class LoginListener
{
    public function handle(Login $event): void
    {
        if (! $event->user instanceof User) {
            return;
        }

        $event->user->update(['last_login' => Carbon::now()]);
    }
}
