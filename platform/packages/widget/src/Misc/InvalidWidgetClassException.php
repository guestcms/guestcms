<?php

namespace Guestcms\Widget\Misc;

use Guestcms\Widget\AbstractWidget;
use Exception;

class InvalidWidgetClassException extends Exception
{
    protected $message = 'Widget class must extend class ' . AbstractWidget::class;
}
