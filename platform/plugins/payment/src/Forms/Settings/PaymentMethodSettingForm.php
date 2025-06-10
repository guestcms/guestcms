<?php

namespace Guestcms\Payment\Forms\Settings;

use Guestcms\Base\Facades\Assets;
use Guestcms\Base\Forms\FieldOptions\SelectFieldOption;
use Guestcms\Base\Forms\Fields\SelectField;
use Guestcms\Payment\Enums\PaymentMethodEnum;
use Guestcms\Payment\Http\Requests\Settings\PaymentMethodSettingRequest;
use Guestcms\Payment\Supports\PaymentHelper;
use Guestcms\Setting\Forms\SettingForm;

class PaymentMethodSettingForm extends SettingForm
{
    public function setup(): void
    {
        parent::setup();

        Assets::addStylesDirectly('vendor/core/plugins/payment/css/payment-setting.css');

        $this
            ->contentOnly()
            ->setSectionTitle(trans('plugins/payment::payment.payment_methods'))
            ->setSectionDescription(trans('plugins/payment::payment.payment_methods_description'))
            ->setValidatorClass(PaymentMethodSettingRequest::class)
            ->setUrl(route('payments.settings'))
            ->add(
                'default_payment_method',
                SelectField::class,
                SelectFieldOption::make()
                    ->label(trans('plugins/payment::payment.default_payment_method'))
                    ->choices(PaymentMethodEnum::labels())
                    ->selected(PaymentHelper::defaultPaymentMethod()),
            );
    }
}
