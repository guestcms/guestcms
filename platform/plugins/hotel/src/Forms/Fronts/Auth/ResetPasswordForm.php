<?php

namespace Guestcms\Hotel\Forms\Fronts\Auth;

use Guestcms\Base\Forms\Fields\EmailField;
use Guestcms\Base\Forms\Fields\HtmlField;
use Guestcms\Hotel\Forms\Fronts\Auth\FieldOptions\EmailFieldOption;
use Guestcms\Hotel\Forms\Fronts\Auth\FieldOptions\TextFieldOption;
use Guestcms\Hotel\Http\Requests\Fronts\Auth\ResetPasswordRequest;

class ResetPasswordForm extends AuthForm
{
    public function setup(): void
    {
        parent::setup();

        $this
            ->setUrl(route('customer.password.reset.update'))
            ->icon('ti ti-lock')
            ->setValidatorClass(ResetPasswordRequest::class)
            ->heading(__('Reset Password'))
            ->add(
                'token',
                'hidden',
                TextFieldOption::make()
                    ->value($this->request->route('token'))
                    ->toArray()
            )
            ->add(
                'email',
                EmailField::class,
                EmailFieldOption::make()
                    ->label(__('Email address'))
                    ->value($this->request->email)
                    ->icon('ti ti-mail')
                    ->toArray()
            )
            ->add(
                'password',
                'password',
                TextFieldOption::make()
                    ->label(__('Password'))
                    ->icon('ti ti-lock')
                    ->toArray()
            )
            ->add(
                'password_confirmation',
                'password',
                TextFieldOption::make()
                    ->label(__('Password confirmation'))
                    ->icon('ti ti-lock')
                    ->toArray()
            )
            ->submitButton(__('Reset Password'))
            ->add('back_to_login', HtmlField::class, [
                'html' => sprintf(
                    '<div class="mt-3 text-center"><a href="%s" class="text-decoration-underline">%s</a></div>',
                    route('customer.login'),
                    __('Back to login page')
                ),
            ]);
    }
}
