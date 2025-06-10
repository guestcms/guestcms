<?php

namespace Guestcms\Base\Forms\FieldTypes;

use Guestcms\Base\Traits\Forms\CanSpanColumns;

class CollectionType extends \Kris\LaravelFormBuilder\Fields\CollectionType
{
    use CanSpanColumns;
}
