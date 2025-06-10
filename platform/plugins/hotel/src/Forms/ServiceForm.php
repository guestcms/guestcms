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
use Guestcms\Hotel\Enums\ServicePriceTypeEnum;
use Guestcms\Hotel\Http\Requests\ServiceRequest;
use Guestcms\Hotel\Models\Service;

class ServiceForm extends FormAbstract
{
    public function setup(): void
    {
        $this
            ->setupModel(new Service())
            ->setValidatorClass(ServiceRequest::class)
            ->withCustomFields()
            ->add('name', TextField::class, NameFieldOption::make()->required()->toArray())
            ->add('description', TextareaField::class, DescriptionFieldOption::make()->toArray())
            ->add('content', EditorField::class, ContentFieldOption::make()->toArray())
            ->add('status', SelectField::class, StatusFieldOption::make()->toArray())
            ->add('price', 'text', [
                'label' => trans('plugins/hotel::service.form.price'),
                'required' => true,
                'attr' => [
                    'id' => 'price-number',
                    'placeholder' => trans('plugins/hotel::service.form.price'),
                    'class' => 'form-control input-mask-number',
                ],
            ])
            ->add('price_type', 'customSelect', [
                'label' => trans('plugins/hotel::service.form.price_type'),
                'attr' => [
                    'class' => 'form-control select-full',
                ],
                'choices' => ServicePriceTypeEnum::labels(),
            ])
            ->add('image', MediaImageField::class)
            ->setBreakFieldPoint('status');
    }
}
