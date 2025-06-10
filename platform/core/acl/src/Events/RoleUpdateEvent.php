<?php

namespace Guestcms\ACL\Events;

use Guestcms\ACL\Models\Role;
use Guestcms\Base\Events\Event;
use Illuminate\Queue\SerializesModels;

class RoleUpdateEvent extends Event
{
    use SerializesModels;

    public function __construct(public Role $role)
    {
    }
}
