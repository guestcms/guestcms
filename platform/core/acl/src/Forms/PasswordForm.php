<?php

namespace Guestcms\ACL\Forms;

use Guestcms\ACL\Http\Requests\UpdatePasswordRequest;
use Guestcms\ACL\Models\User;
use Guestcms\Base\Forms\FieldOptions\TextFieldOption;
use Guestcms\Base\Forms\FormAbstract;

class PasswordForm extends FormAbstract
{
    public function setup(): void
    {
        $this
            ->model(User::class)
            ->setValidatorClass(UpdatePasswordRequest::class)
            ->template('core/base::forms.form-no-wrap')
            ->setFormOption('id', 'password-form')
            ->setMethod('PUT')
            ->columns()
            ->when(
                $this->getModel()->exists &&
                $this->getRequest()->user()->is($this->getModel()),
                function (FormAbstract $form): void {
                    $form->add(
                        'old_password',
                        'password',
                        TextFieldOption::make()
                            ->label(trans('core/acl::users.current_password'))
                            ->required()
                            ->maxLength(60)
                            ->colspan(2)
                    );
                }
            )
            ->add(
                'password',
                'password',
                TextFieldOption::make()
                    ->label(trans('core/acl::users.new_password'))
                    ->required()
                    ->maxLength(60)
            )
            ->add(
                'password_confirmation',
                'password',
                TextFieldOption::make()
                    ->label(trans('core/acl::users.confirm_new_password'))
                    ->required()
                    ->maxLength(60)
            )
            ->setActionButtons(view('core/acl::users.profile.actions')->render());
    }
}
