<?php

namespace Guestcms\ACL\Forms\Auth;

use Guestcms\ACL\Http\Requests\LoginRequest;
use Guestcms\ACL\Models\User;
use Guestcms\Base\Facades\BaseHelper;
use Guestcms\Base\Facades\Html;
use Guestcms\Base\Forms\FieldOptions\CheckboxFieldOption;
use Guestcms\Base\Forms\FieldOptions\HtmlFieldOption;
use Guestcms\Base\Forms\FieldOptions\TextFieldOption;
use Guestcms\Base\Forms\Fields\CheckboxField;
use Guestcms\Base\Forms\Fields\HtmlField;
use Guestcms\Base\Forms\Fields\PasswordField;
use Guestcms\Base\Forms\Fields\TextField;

class LoginForm extends AuthForm
{
    public function setup(): void
    {
        parent::setup();

        $this
            ->setValidatorClass(LoginRequest::class)
            ->setUrl(route('access.login'))
            ->heading(trans('core/acl::auth.sign_in_below'))
            ->add(
                'username',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('core/acl::auth.login.username'))
                    ->value(old(
                        'email',
                        BaseHelper::hasDemoModeEnabled() ? config('core.base.general.demo.account.username') : null,
                    ))
                    ->required()
                    ->attributes(['tabindex' => 1, 'placeholder' => trans('core/acl::auth.login.placeholder.username')])
            )
            ->add(
                'password',
                PasswordField::class,
                TextFieldOption::make()
                    ->label(trans('core/acl::auth.login.password'))
                    ->labelAttributes([
                        'description' => Html::link(route('access.password.request'), trans('core/acl::auth.lost_your_password'), ['tabindex' => 5, 'title' => trans('core/acl::auth.forgot_password.title')])->toHtml(),
                    ])
                    ->attributes(['tabindex' => 2])
                    ->when(BaseHelper::hasDemoModeEnabled(), function (TextFieldOption $option): void {
                        $option->value(config('core.base.general.demo.account.password'));
                    })
                    ->required()
                    ->placeholder(trans('core/acl::auth.login.placeholder.password'))
            )
            ->add(
                'remember',
                CheckboxField::class,
                CheckboxFieldOption::make()
                    ->label(trans('core/acl::auth.login.remember'))
                    ->value(true)
                    ->attributes(['tabindex' => 3])
            )
            ->submitButton(trans('core/acl::auth.login.login'), 'ti ti-login-2')
            ->add(
                'filters',
                HtmlField::class,
                HtmlFieldOption::make()
                    ->content(apply_filters(BASE_FILTER_AFTER_LOGIN_OR_REGISTER_FORM, null, User::class))
            );
    }
}
