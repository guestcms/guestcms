<?php

namespace Guestcms\Hotel\Forms;

use Guestcms\Base\Forms\FieldOptions\NameFieldOption;
use Guestcms\Base\Forms\FieldOptions\OnOffFieldOption;
use Guestcms\Base\Forms\FieldOptions\StatusFieldOption;
use Guestcms\Base\Forms\Fields\OnOffField;
use Guestcms\Base\Forms\Fields\SelectField;
use Guestcms\Base\Forms\Fields\TextField;
use Guestcms\Base\Forms\FormAbstract;
use Guestcms\Hotel\Http\Requests\RoomCategoryRequest;
use Guestcms\Hotel\Models\RoomCategory;

class RoomCategoryForm extends FormAbstract
{
    public function setup(): void
    {
        $this
            ->setupModel(new RoomCategory())
            ->setValidatorClass(RoomCategoryRequest::class)
            ->withCustomFields()
            ->add('name', TextField::class, NameFieldOption::make()->required()->toArray())
            ->add('order', 'number', [
                'label' => trans('core/base::forms.order'),
                'attr' => [
                    'placeholder' => trans('core/base::forms.order_by_placeholder'),
                ],
                'default_value' => 0,
            ])
            ->add(
                'is_featured',
                OnOffField::class,
                OnOffFieldOption::make()
                    ->label(trans('core/base::forms.is_featured'))
                    ->defaultValue(false)
                    ->toArray()
            )
            ->add('status', SelectField::class, StatusFieldOption::make()->toArray())
            ->setBreakFieldPoint('status');
    }
}
