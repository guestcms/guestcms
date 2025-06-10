<?php

namespace Guestcms\Base\Forms\FieldTypes;

use Guestcms\Base\Traits\Forms\CanSpanColumns;

class CheckableType extends \Kris\LaravelFormBuilder\Fields\CheckableType
{
    use CanSpanColumns;
}
