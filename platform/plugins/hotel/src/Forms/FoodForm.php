<?php

namespace Guestcms\Hotel\Forms;

use Guestcms\Base\Facades\Assets;
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
use Guestcms\Hotel\Http\Requests\FoodRequest;
use Guestcms\Hotel\Models\Food;
use Guestcms\Hotel\Models\FoodType;

class FoodForm extends FormAbstract
{
    public function setup(): void
    {
        Assets::addScripts(['input-mask'])
            ->addStylesDirectly('vendor/core/plugins/hotel/css/hotel.css');

        $foodTypes = FoodType::query()->pluck('name', 'id')->all();

        $this
            ->setupModel(new Food())
            ->setValidatorClass(FoodRequest::class)
            ->withCustomFields()
            ->add('name', TextField::class, NameFieldOption::make()->required()->toArray())
            ->add('description', TextareaField::class, DescriptionFieldOption::make()->toArray())
            ->add('content', EditorField::class, ContentFieldOption::make())
            ->add('price', 'text', [
                'label' => trans('plugins/hotel::food.form.price'),
                'required' => true,
                'attr' => [
                    'id' => 'price-number',
                    'placeholder' => trans('plugins/hotel::food.form.price'),
                    'class' => 'form-control input-mask-number',
                ],
            ])
            ->add('status', SelectField::class, StatusFieldOption::make()->toArray())
            ->add('food_type_id', 'customSelect', [
                'label' => trans('plugins/hotel::food.form.food_type'),
                'required' => true,
                'choices' => $foodTypes,
            ])
            ->add('image', MediaImageField::class)
            ->setBreakFieldPoint('status');
    }
}
