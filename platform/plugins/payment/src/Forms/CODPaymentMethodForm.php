<?php

namespace Guestcms\Payment\Forms;

use Guestcms\Base\Forms\FieldOptions\EditorFieldOption;
use Guestcms\Base\Forms\FieldOptions\TextFieldOption;
use Guestcms\Base\Forms\Fields\EditorField;
use Guestcms\Base\Forms\Fields\HtmlField;
use Guestcms\Base\Forms\Fields\TextField;
use Guestcms\Base\Forms\FormAbstract;
use Guestcms\Payment\Concerns\Forms\HasAvailableCountriesField;
use Guestcms\Payment\Enums\PaymentMethodEnum;

class CODPaymentMethodForm extends PaymentMethodForm
{
    use HasAvailableCountriesField;

    public function setup(): void
    {
        $this
            ->template('plugins/payment::forms.fields-only')
            ->add(
                'type',
                'hidden',
                TextFieldOption::make()
                    ->value(PaymentMethodEnum::COD)
                    ->attributes(['class' => 'payment_type'])
            )
            ->add(
                get_payment_setting_key('name', PaymentMethodEnum::COD),
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/payment::payment.method_name'))
                    ->attributes(['data-counter' => 400])
                    ->value(get_payment_setting(
                        'name',
                        PaymentMethodEnum::COD,
                        PaymentMethodEnum::COD()->label(),
                    )),
            )
            ->add(
                get_payment_setting_key('description', PaymentMethodEnum::COD),
                EditorField::class,
                EditorFieldOption::make()
                    ->wrapperAttributes(['style' => 'max-width: 99.8%'])
                    ->label(trans('plugins/payment::payment.payment_method_description'))
                    ->value(get_payment_setting('description', PaymentMethodEnum::COD))
            )
            ->paymentMethodLogoField(PaymentMethodEnum::COD)
            ->paymentFeeField(PaymentMethodEnum::COD)
            ->addAvailableCountriesField(PaymentMethodEnum::COD)
            ->when(
                apply_filters(PAYMENT_METHOD_SETTINGS_CONTENT, null, PaymentMethodEnum::COD),
                function (FormAbstract $form, ?string $data): void {
                    $form->add('metabox', HtmlField::class, ['html' => $data]);
                }
            );
    }
}
