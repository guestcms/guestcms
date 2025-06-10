<?php

namespace Guestcms\ACL\Events;

use Guestcms\ACL\Models\Role;
use Guestcms\ACL\Models\User;
use Guestcms\Base\Events\Event;
use Illuminate\Queue\SerializesModels;

class RoleAssignmentEvent extends Event
{
    use SerializesModels;

    public function __construct(public Role $role, public User $user)
    {
    }
}
