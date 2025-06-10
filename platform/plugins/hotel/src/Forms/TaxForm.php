<?php

namespace Guestcms\Hotel\Forms;

use Guestcms\Base\Forms\FieldOptions\StatusFieldOption;
use Guestcms\Base\Forms\Fields\SelectField;
use Guestcms\Base\Forms\FormAbstract;
use Guestcms\Hotel\Http\Requests\TaxRequest;
use Guestcms\Hotel\Models\Tax;

class TaxForm extends FormAbstract
{
    public function setup(): void
    {
        $this
            ->setupModel(new Tax())
            ->setValidatorClass(TaxRequest::class)
            ->withCustomFields()
            ->add('title', 'text', [
                'label' => trans('plugins/hotel::tax.title'),
                'required' => true,
                'attr' => [
                    'placeholder' => trans('plugins/hotel::tax.title'),
                    'data-counter' => 120,
                ],
            ])
            ->add('percentage', 'number', [
                'label' => trans('plugins/hotel::tax.percentage'),
                'required' => true,
                'attr' => [
                    'placeholder' => trans('plugins/hotel::tax.percentage'),
                    'data-counter' => 120,
                ],
            ])
            ->add('priority', 'number', [
                'label' => trans('plugins/hotel::tax.priority'),
                'required' => true,
                'attr' => [
                    'placeholder' => trans('plugins/hotel::tax.priority'),
                    'data-counter' => 120,
                ],
            ])
            ->add('status', SelectField::class, StatusFieldOption::make()->toArray())
            ->setBreakFieldPoint('status');
    }
}
