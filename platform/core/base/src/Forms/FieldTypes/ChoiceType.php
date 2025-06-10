<?php

namespace Guestcms\Base\Forms\FieldTypes;

use Guestcms\Base\Traits\Forms\CanSpanColumns;

class ChoiceType extends \Kris\LaravelFormBuilder\Fields\ChoiceType
{
    use CanSpanColumns;
}
