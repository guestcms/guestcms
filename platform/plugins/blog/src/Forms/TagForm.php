<?php

namespace Guestcms\Blog\Forms;

use Guestcms\Base\Forms\FieldOptions\DescriptionFieldOption;
use Guestcms\Base\Forms\FieldOptions\NameFieldOption;
use Guestcms\Base\Forms\FieldOptions\StatusFieldOption;
use Guestcms\Base\Forms\Fields\SelectField;
use Guestcms\Base\Forms\Fields\TextareaField;
use Guestcms\Base\Forms\Fields\TextField;
use Guestcms\Base\Forms\FormAbstract;
use Guestcms\Blog\Http\Requests\TagRequest;
use Guestcms\Blog\Models\Tag;

class TagForm extends FormAbstract
{
    public function setup(): void
    {
        $this
            ->model(Tag::class)
            ->setValidatorClass(TagRequest::class)
            ->add('name', TextField::class, NameFieldOption::make()->required()->maxLength(120))
            ->add('description', TextareaField::class, DescriptionFieldOption::make())
            ->add('status', SelectField::class, StatusFieldOption::make())
            ->setBreakFieldPoint('status');
    }
}
