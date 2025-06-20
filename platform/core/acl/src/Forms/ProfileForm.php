<?php

namespace Guestcms\ACL\Forms;

use Guestcms\ACL\Http\Requests\UpdateProfileRequest;
use Guestcms\ACL\Models\User;
use Guestcms\Base\Forms\FieldOptions\EmailFieldOption;
use Guestcms\Base\Forms\FieldOptions\TextFieldOption;
use Guestcms\Base\Forms\Fields\TextField;
use Guestcms\Base\Forms\FormAbstract;

class ProfileForm extends FormAbstract
{
    public function setup(): void
    {
        $this
            ->model(User::class)
            ->template('core/base::forms.form-no-wrap')
            ->setFormOption('id', 'profile-form')
            ->setValidatorClass(UpdateProfileRequest::class)
            ->setMethod('PUT')
            ->columns()
            ->add(
                'first_name',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('core/acl::users.info.first_name'))
                    ->required()
                    ->maxLength(30)
            )
            ->add(
                'last_name',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('core/acl::users.info.last_name'))
                    ->required()
                    ->maxLength(30)
            )
            ->add(
                'username',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('core/acl::users.username'))
                    ->required()
                    ->maxLength(30)
            )
            ->add('email', TextField::class, EmailFieldOption::make()->required())
            ->setActionButtons(view('core/acl::users.profile.actions')->render());
    }
}
