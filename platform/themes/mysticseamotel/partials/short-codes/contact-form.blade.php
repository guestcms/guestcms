{!!
    $form
        ->setFormOption('class', 'contact-form')
        ->setFormInputClass(' ')
        ->setFormInputWrapperClass('input-group mt-30')
        ->modify(
            'name',
            \Guestcms\Base\Forms\Fields\TextField::class,
            \Guestcms\Base\Forms\FieldOptions\TextFieldOption::make()
                ->prepend('<span class="icon"><i class="far fa-user"></i></span>')
        )
        ->modify(
            'email',
            \Guestcms\Base\Forms\Fields\EmailField::class,
            \Guestcms\Base\Forms\FieldOptions\EmailFieldOption::make()
                ->prepend('<span class="icon"><i class="far fa-envelope"></i></span>')
        )
        ->modify(
            'address',
            \Guestcms\Base\Forms\Fields\TextField::class,
            \Guestcms\Base\Forms\FieldOptions\TextFieldOption::make()
                ->prepend('<span class="icon"><i class="fal fa-map-marker-alt"></i></span>')
        )
        ->modify(
            'phone',
            \Guestcms\Base\Forms\Fields\TextField::class,
            \Guestcms\Base\Forms\FieldOptions\TextFieldOption::make()
                ->prepend('<span class="icon"><i class="fa fa-phone"></i></span>')
        )
        ->modify(
            'subject',
            \Guestcms\Base\Forms\Fields\TextField::class,
            \Guestcms\Base\Forms\FieldOptions\TextFieldOption::make()
                ->prepend('<span class="icon"><i class="far fa-book"></i></span>')
        )
        ->modify(
            'content',
            \Guestcms\Base\Forms\Fields\TextareaField::class,
            \Guestcms\Base\Forms\FieldOptions\TextareaFieldOption::make()
                ->prepend('<span class="icon textarea-icon"><i class="far fa-pen"></i></span>')
        )
        ->modify(
            'submit',
            'submit',
            Guestcms\Base\Forms\FieldOptions\ButtonFieldOption::make()
                ->cssClass('main-btn btn-filled')
                ->label(__('Submit'))
                ->wrapperAttributes(['class' => 'input-group mt-30 mb-30'])
                ->toArray(),
            true
        )
        ->renderForm()
    !!}