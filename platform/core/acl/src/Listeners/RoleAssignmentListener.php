<?php

namespace Guestcms\ACL\Listeners;

use Guestcms\ACL\Events\RoleAssignmentEvent;

class RoleAssignmentListener
{
    public function handle(RoleAssignmentEvent $event): void
    {
        $permissions = $event->role->permissions;
        $permissions[ACL_ROLE_SUPER_USER] = $event->user->super_user;
        $permissions[ACL_ROLE_MANAGE_SUPERS] = $event->user->manage_supers;

        $event->user->permissions = $permissions;
        $event->user->save();
    }
}
