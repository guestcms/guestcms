<?php

namespace Guestcms\Base\Forms\FieldTypes;

use Guestcms\Base\Traits\Forms\CanSpanColumns;

class ChildFormType extends \Kris\LaravelFormBuilder\Fields\ChildFormType
{
    use CanSpanColumns;
}
