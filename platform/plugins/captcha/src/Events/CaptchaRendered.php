<?php

namespace Guestcms\Captcha\Events;

use Illuminate\Foundation\Events\Dispatchable;

class CaptchaRendered
{
    use Dispatchable;

    public function __construct(public string $rendered = '')
    {
    }
}
