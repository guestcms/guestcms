<?php

namespace Guestcms\Hotel\Forms;

use Guestcms\Base\Forms\FieldOptions\ContentFieldOption;
use Guestcms\Base\Forms\FieldOptions\DescriptionFieldOption;
use Guestcms\Base\Forms\FieldOptions\NameFieldOption;
use Guestcms\Base\Forms\FieldOptions\StatusFieldOption;
use Guestcms\Base\Forms\Fields\EditorField;
use Guestcms\Base\Forms\Fields\MediaImageField;
use Guestcms\Base\Forms\Fields\SelectField;
use Guestcms\Base\Forms\Fields\TextareaField;
use Guestcms\Base\Forms\Fields\TextField;
use Guestcms\Base\Forms\FormAbstract;
use Guestcms\Hotel\Http\Requests\PlaceRequest;
use Guestcms\Hotel\Models\Place;

class PlaceForm extends FormAbstract
{
    public function setup(): void
    {
        $this
            ->setupModel(new Place())
            ->setValidatorClass(PlaceRequest::class)
            ->withCustomFields()
            ->add('name', TextField::class, NameFieldOption::make()->required()->toArray())
            ->add('distance', 'text', [
                'label' => trans('plugins/hotel::place.form.distance'),
                'required' => true,
                'attr' => [
                    'placeholder' => trans('plugins/hotel::place.form.distance_placeholder'),
                    'data-counter' => 200,
                ],
            ])
            ->add('description', TextareaField::class, DescriptionFieldOption::make()->toArray())
            ->add('content', EditorField::class, ContentFieldOption::make()->toArray())
            ->add('status', SelectField::class, StatusFieldOption::make()->toArray())
            ->add('image', MediaImageField::class)
            ->setBreakFieldPoint('status');
    }
}
