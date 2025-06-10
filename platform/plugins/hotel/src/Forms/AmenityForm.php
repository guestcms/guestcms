<?php

namespace Guestcms\Hotel\Forms;

use Guestcms\Base\Forms\FieldOptions\NameFieldOption;
use Guestcms\Base\Forms\FieldOptions\StatusFieldOption;
use Guestcms\Base\Forms\Fields\SelectField;
use Guestcms\Base\Forms\Fields\TextField;
use Guestcms\Base\Forms\FormAbstract;
use Guestcms\Hotel\Http\Requests\AmenityRequest;
use Guestcms\Hotel\Models\Amenity;

class AmenityForm extends FormAbstract
{
    public function setup(): void
    {
        $this
            ->setupModel(new Amenity())
            ->setValidatorClass(AmenityRequest::class)
            ->withCustomFields()
            ->add('name', TextField::class, NameFieldOption::make()->required()->toArray())
            ->add('icon', 'themeIcon', [
                'label' => trans('plugins/hotel::amenity.icon'),
                'default_value' => 'fa fa-check',
            ])
            ->add('status', SelectField::class, StatusFieldOption::make()->toArray())
            ->setBreakFieldPoint('status');
    }
}
