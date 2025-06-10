<?php

namespace Guestcms\Base\Events;

use Guestcms\Base\Contracts\BaseModel;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;

class BeforeEditContentEvent extends Event
{
    use SerializesModels;

    public function __construct(public Request $request, public bool|BaseModel|null $data)
    {
    }
}
