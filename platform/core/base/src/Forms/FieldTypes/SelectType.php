<?php

namespace Guestcms\Base\Forms\FieldTypes;

use Guestcms\Base\Traits\Forms\CanSpanColumns;

class SelectType extends \Kris\LaravelFormBuilder\Fields\SelectType
{
    use CanSpanColumns;
}
