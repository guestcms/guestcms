<?php

namespace Guestcms\Hotel\Forms;

use Guestcms\Base\Forms\FieldOptions\DescriptionFieldOption;
use Guestcms\Base\Forms\FieldOptions\NameFieldOption;
use Guestcms\Base\Forms\FieldOptions\OnOffFieldOption;
use Guestcms\Base\Forms\FieldOptions\StatusFieldOption;
use Guestcms\Base\Forms\Fields\OnOffField;
use Guestcms\Base\Forms\Fields\SelectField;
use Guestcms\Base\Forms\Fields\TextareaField;
use Guestcms\Base\Forms\Fields\TextField;
use Guestcms\Base\Forms\FormAbstract;
use Guestcms\Hotel\Http\Requests\FeatureRequest;
use Guestcms\Hotel\Models\Feature;

class FeatureForm extends FormAbstract
{
    public function setup(): void
    {
        $this
            ->setupModel(new Feature())
            ->setValidatorClass(FeatureRequest::class)
            ->withCustomFields()
            ->add('name', TextField::class, NameFieldOption::make()->required()->toArray())
            ->add('icon', 'themeIcon', [
                'label' => trans('plugins/hotel::feature.form.icon'),
                'default_value' => 'fa fa-check',
            ])
            ->add(
                'is_featured',
                OnOffField::class,
                OnOffFieldOption::make()
                    ->label(trans('core/base::forms.is_featured'))
                    ->defaultValue(false)
                    ->toArray()
            )
            ->add('description', TextareaField::class, DescriptionFieldOption::make()->toArray())
            ->add('status', SelectField::class, StatusFieldOption::make()->toArray())
            ->setBreakFieldPoint('status');
    }
}
