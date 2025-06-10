<?php

namespace Guestcms\Base\Forms\FieldTypes;

use Guestcms\Base\Traits\Forms\CanSpanColumns;

class StaticType extends \Kris\LaravelFormBuilder\Fields\StaticType
{
    use CanSpanColumns;
}
