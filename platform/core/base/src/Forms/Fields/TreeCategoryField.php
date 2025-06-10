<?php

namespace Guestcms\Base\Forms\Fields;

use Guestcms\Base\Forms\FieldOptions\TreeCategoryFieldOption;
use Guestcms\Base\Forms\FormField;

class TreeCategoryField extends FormField
{
    public function getFieldOption(): string
    {
        return TreeCategoryFieldOption::class;
    }

    protected function getTemplate(): string
    {
        return 'core/base::forms.fields.tree-categories';
    }
}
