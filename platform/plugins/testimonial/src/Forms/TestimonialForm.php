<?php

namespace Guestcms\Testimonial\Forms;

use Guestcms\Base\Forms\FieldOptions\ContentFieldOption;
use Guestcms\Base\Forms\FieldOptions\MediaImageFieldOption;
use Guestcms\Base\Forms\FieldOptions\NameFieldOption;
use Guestcms\Base\Forms\FieldOptions\StatusFieldOption;
use Guestcms\Base\Forms\FieldOptions\TextFieldOption;
use Guestcms\Base\Forms\Fields\EditorField;
use Guestcms\Base\Forms\Fields\MediaImageField;
use Guestcms\Base\Forms\Fields\SelectField;
use Guestcms\Base\Forms\Fields\TextField;
use Guestcms\Base\Forms\FormAbstract;
use Guestcms\Testimonial\Http\Requests\TestimonialRequest;
use Guestcms\Testimonial\Models\Testimonial;

class TestimonialForm extends FormAbstract
{
    public function setup(): void
    {
        $this
            ->model(Testimonial::class)
            ->setValidatorClass(TestimonialRequest::class)
            ->add('name', TextField::class, NameFieldOption::make()->required())
            ->add(
                'company',
                TextField::class,
                TextFieldOption::make()->label(trans('plugins/testimonial::testimonial.company'))->maxLength(
                    120
                )
            )
            ->add('content', EditorField::class, ContentFieldOption::make()->required())
            ->add('status', SelectField::class, StatusFieldOption::make())
            ->add('image', MediaImageField::class, MediaImageFieldOption::make())
            ->setBreakFieldPoint('status');
    }
}
