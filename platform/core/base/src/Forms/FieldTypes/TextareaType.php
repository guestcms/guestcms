<?php

namespace Guestcms\Base\Forms\FieldTypes;

use Guestcms\Base\Traits\Forms\CanSpanColumns;

class TextareaType extends \Kris\LaravelFormBuilder\Fields\TextareaType
{
    use CanSpanColumns;
}
