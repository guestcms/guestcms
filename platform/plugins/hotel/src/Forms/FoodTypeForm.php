<?php

namespace Guestcms\Hotel\Forms;

use Guestcms\Base\Forms\FieldOptions\NameFieldOption;
use Guestcms\Base\Forms\FieldOptions\StatusFieldOption;
use Guestcms\Base\Forms\Fields\SelectField;
use Guestcms\Base\Forms\Fields\TextField;
use Guestcms\Base\Forms\FormAbstract;
use Guestcms\Hotel\Http\Requests\FoodTypeRequest;
use Guestcms\Hotel\Models\FoodType;

class FoodTypeForm extends FormAbstract
{
    public function setup(): void
    {
        $this
            ->setupModel(new FoodType())
            ->setValidatorClass(FoodTypeRequest::class)
            ->withCustomFields()
            ->add('name', TextField::class, NameFieldOption::make()->required()->toArray())
            ->add('icon', 'themeIcon', [
                'label' => trans('plugins/hotel::food-type.form.icon'),
                'attr' => [
                    'class' => 'form-control select-full',
                ],
                'default_value' => 'fa fa-check',
            ])
            ->add('status', SelectField::class, StatusFieldOption::make()->toArray())
            ->setBreakFieldPoint('status');
    }
}
