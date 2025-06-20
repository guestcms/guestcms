<?php

namespace Guestcms\Captcha\Providers;

use Guestcms\ACL\Forms\Auth\ForgotPasswordForm;
use Guestcms\ACL\Forms\Auth\LoginForm;
use Guestcms\ACL\Forms\Auth\ResetPasswordForm;
use Guestcms\ACL\Http\Requests\ForgotPasswordRequest;
use Guestcms\ACL\Http\Requests\LoginRequest;
use Guestcms\ACL\Http\Requests\ResetPasswordRequest;
use Guestcms\Base\Facades\PanelSectionManager;
use Guestcms\Base\Forms\FormAbstract;
use Guestcms\Base\PanelSections\PanelSectionItem;
use Guestcms\Base\Supports\ServiceProvider;
use Guestcms\Base\Traits\LoadAndPublishDataTrait;
use Guestcms\Captcha\Captcha;
use Guestcms\Captcha\CaptchaV3;
use Guestcms\Captcha\Facades\Captcha as CaptchaFacade;
use Guestcms\Captcha\Forms\Fields\MathCaptchaField;
use Guestcms\Captcha\Forms\Fields\ReCaptchaField;
use Guestcms\Captcha\MathCaptcha;
use Guestcms\Setting\PanelSections\SettingOthersPanelSection;
use Guestcms\Support\Http\Requests\Request;
use Guestcms\Theme\FormFront;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Http\Request as IlluminateRequest;
use Illuminate\Routing\Events\Routing;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

class CaptchaServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    protected bool $defer = false;

    public function register(): void
    {
        $this->app->singleton('captcha', function () {
            $key = setting('captcha_site_key');
            $secret = setting('captcha_secret');

            if (setting('captcha_type') === 'v3') {
                return new CaptchaV3($key, $secret);
            }

            return new Captcha($key, $secret);
        });

        $this->app->singleton('math-captcha', function ($app) {
            return new MathCaptcha($app['session']);
        });

        AliasLoader::getInstance()->alias('Captcha', CaptchaFacade::class);
    }

    public function boot(): void
    {
        $this
            ->setNamespace('plugins/captcha')
            ->loadAndPublishConfigurations(['general', 'permissions'])
            ->loadRoutes()
            ->loadAndPublishViews()
            ->loadAndPublishTranslations();

        $this->bootValidator();

        PanelSectionManager::default()->beforeRendering(function (): void {
            PanelSectionManager::registerItem(
                SettingOthersPanelSection::class,
                fn () => PanelSectionItem::make('captcha')
                    ->setTitle(trans('plugins/captcha::captcha.settings.title'))
                    ->withIcon('ti ti-refresh')
                    ->withPriority(150)
                    ->withDescription(trans('plugins/captcha::captcha.settings.panel_description'))
                    ->withRoute('captcha.settings')
            );
        });

        CaptchaFacade::registerFormSupport(LoginForm::class, LoginRequest::class, trans('plugins/captcha::captcha.admin_login_form'));
        CaptchaFacade::registerFormSupport(ForgotPasswordForm::class, ForgotPasswordRequest::class, trans('plugins/captcha::captcha.admin_forgot_password_form'));
        CaptchaFacade::registerFormSupport(ResetPasswordForm::class, ResetPasswordRequest::class, trans('plugins/captcha::captcha.admin_reset_password_form'));

        FormAbstract::beforeRendering(function (FormAbstract $form): void {
            if (! CaptchaFacade::isEnabled() && ! CaptchaFacade::mathCaptchaEnabled()) {
                return;
            }

            $fieldKey = 'submit';

            $attributes = [
                'colspan' => $form->getColumns('lg'),
            ];

            if ($form instanceof FormFront) {
                $fieldKey = $form->getFormEndKey() ?: ($form->has($fieldKey) ? $fieldKey : array_key_last($form->getFields()));

                if ($form->getFormInputWrapperClass()) {
                    $attributes['wrapper'] = ['class' => $form->getFormInputWrapperClass()];
                }

                if ($form->getFormLabelClass()) {
                    $attributes['label_attr'] = ['class' => $form->getFormLabelClass()];
                }

                if ($form->getFormInputClass()) {
                    $attributes['attr'] = ['class' => $form->getFormInputClass()];
                }
            }

            if (CaptchaFacade::reCaptchaEnabled() && ! $form->has('recaptcha') && CaptchaFacade::formSetting($form::class, 'enable_recaptcha')) {
                $form->addBefore(
                    $fieldKey,
                    'recaptcha',
                    ReCaptchaField::class,
                    $attributes
                );
            }

            if (CaptchaFacade::mathCaptchaEnabled() && ! $form->has('math_captcha') && CaptchaFacade::formSetting($form::class, 'enable_math_captcha')) {
                $form->addBefore(
                    $fieldKey,
                    'math_captcha',
                    MathCaptchaField::class,
                    $attributes
                );
            }
        });

        $this->app['events']->listen(Routing::class, function (): void {
            add_filter('core_request_rules', function (array $rules, Request $request) {
                if (! CaptchaFacade::isEnabled() && ! CaptchaFacade::mathCaptchaEnabled()) {
                    return $rules;
                }

                CaptchaFacade::getFormsSupport();

                $form = CaptchaFacade::formByRequest($request::class);

                if (! $form) {
                    return $rules;
                }

                if (CaptchaFacade::reCaptchaEnabled() && CaptchaFacade::formSetting($form, 'enable_recaptcha')) {
                    $rules = [...$rules, ...CaptchaFacade::rules()];
                }

                if (CaptchaFacade::mathCaptchaEnabled() && CaptchaFacade::formSetting($form, 'enable_math_captcha')) {
                    $rules = [...$rules, ...CaptchaFacade::mathCaptchaRules()];
                }

                return $rules;
            }, 128, 2);
        });

        add_filter('form_extra_fields_render', function (?string $fields = null, ?string $form = null): ?string {
            if (! CaptchaFacade::isEnabled() && ! CaptchaFacade::mathCaptchaEnabled()) {
                return $fields;
            }

            return $fields . view('plugins/captcha::forms.old-version-support', compact('form'))->render();
        }, 128, 2);

        add_action('form_extra_fields_validate', function (IlluminateRequest $request, ?string $form = null): void {
            if (! CaptchaFacade::isEnabled() && ! CaptchaFacade::mathCaptchaEnabled()) {
                return;
            }

            if (
                CaptchaFacade::reCaptchaEnabled()
                && (! $form || ! class_exists($form) || CaptchaFacade::formSetting($form, 'enable_recaptcha'))
                && ! $request instanceof Request
            ) {
                Validator::validate($request->input(), CaptchaFacade::rules());
            }

            if (CaptchaFacade::mathCaptchaEnabled() && (! $form || ! class_exists($form) || CaptchaFacade::formSetting($form, 'enable_math_captcha'))) {
                Validator::validate($request->input(), CaptchaFacade::mathCaptchaRules());
            }
        }, 999, 2);

        add_filter('core_request_messages', function (array $messages): array {
            return [
                ...$messages,
                'captcha' => __('Captcha Verification Failed!'),
                'math_captcha' => __('Math Captcha Verification Failed!'),
            ];
        }, 999);

        add_filter('core_request_attributes', function (array $attributes): array {
            return [
                ...$attributes,
                CaptchaFacade::attributes(),
            ];
        }, 999);
    }

    public function bootValidator(): void
    {
        $app = $this->app;

        /**
         * @var Validator $validator
         */
        $validator = $app['validator'];
        $validator->extend('captcha', function ($attribute, $value, $parameters) use ($app) {
            if (! $app['captcha']->reCaptchaEnabled()) {
                return true;
            }

            if (! is_string($value)) {
                return false;
            }

            if (setting('captcha_type') === 'v3') {
                if (empty($parameters)) {
                    $parameters = ['form', (float) setting('recaptcha_score', 0.6)];
                }
            } else {
                $parameters = $this->mapParameterToOptions($parameters);
            }

            return $app['captcha']->verify($value, $this->app['request']->getClientIp(), $parameters);
        }, __('Captcha Verification Failed!'));

        $validator->extend('math_captcha', function ($attribute, $value) {
            if (! is_string($value)) {
                return false;
            }

            return $this->app['math-captcha']->verify($value);
        }, __('Math Captcha Verification Failed!'));
    }

    public function mapParameterToOptions(?array $parameters = []): array
    {
        if (! is_array($parameters)) {
            return [];
        }

        $options = [];

        foreach ($parameters as $parameter) {
            $option = explode(':', $parameter);
            if (count($option) === 2) {
                Arr::set($options, $option[0], $option[1]);
            }
        }

        return $options;
    }

    public function provides(): array
    {
        return ['captcha', 'math-captcha'];
    }
}
