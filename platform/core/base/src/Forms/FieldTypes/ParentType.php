<?php

namespace Guestcms\Base\Forms\FieldTypes;

use Guestcms\Base\Traits\Forms\CanSpanColumns;

abstract class ParentType extends \Kris\LaravelFormBuilder\Fields\ParentType
{
    use CanSpanColumns;
}
