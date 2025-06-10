<?php

namespace Guestcms\Gallery\Forms;

use Guestcms\Base\Forms\FieldOptions\DescriptionFieldOption;
use Guestcms\Base\Forms\FieldOptions\IsFeaturedFieldOption;
use Guestcms\Base\Forms\FieldOptions\MediaImageFieldOption;
use Guestcms\Base\Forms\FieldOptions\NameFieldOption;
use Guestcms\Base\Forms\FieldOptions\SortOrderFieldOption;
use Guestcms\Base\Forms\FieldOptions\StatusFieldOption;
use Guestcms\Base\Forms\Fields\EditorField;
use Guestcms\Base\Forms\Fields\MediaImageField;
use Guestcms\Base\Forms\Fields\NumberField;
use Guestcms\Base\Forms\Fields\OnOffField;
use Guestcms\Base\Forms\Fields\SelectField;
use Guestcms\Base\Forms\Fields\TextField;
use Guestcms\Base\Forms\FormAbstract;
use Guestcms\Gallery\Http\Requests\GalleryRequest;
use Guestcms\Gallery\Models\Gallery;

class GalleryForm extends FormAbstract
{
    public function setup(): void
    {
        $this
            ->model(Gallery::class)
            ->setValidatorClass(GalleryRequest::class)
            ->add('name', TextField::class, NameFieldOption::make()->required())
            ->add(
                'description',
                EditorField::class,
                DescriptionFieldOption::make()
                    ->required()
            )
            ->add('order', NumberField::class, SortOrderFieldOption::make())
            ->add(
                'is_featured',
                OnOffField::class,
                IsFeaturedFieldOption::make()
            )
            ->add('status', SelectField::class, StatusFieldOption::make())
            ->add('image', MediaImageField::class, MediaImageFieldOption::make())
            ->setBreakFieldPoint('status');
    }
}
