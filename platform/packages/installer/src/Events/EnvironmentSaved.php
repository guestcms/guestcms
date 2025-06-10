<?php

namespace Guestcms\Installer\Events;

use Guestcms\Base\Events\Event;
use Illuminate\Http\Request;

class EnvironmentSaved extends Event
{
    public function __construct(public Request $request)
    {
    }
}
