<?php

namespace Guestcms\Base\Events;

use Guestcms\Base\Forms\FormAbstract;
use Illuminate\Foundation\Events\Dispatchable;

class FormRendering
{
    use Dispatchable;

    public function __construct(public FormAbstract $form)
    {
    }
}
