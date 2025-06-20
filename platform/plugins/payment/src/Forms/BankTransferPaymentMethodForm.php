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

class BankTransferPaymentMethodForm extends PaymentMethodForm
{
    use HasAvailableCountriesField;

    public function setup(): void
    {
        parent::setup();

        $this
            ->template('plugins/payment::forms.fields-only')
            ->add(
                'type',
                'hidden',
                TextFieldOption::make()
                    ->value(PaymentMethodEnum::BANK_TRANSFER)
                    ->attributes(['class' => 'payment_type'])
            )
            ->add(
                get_payment_setting_key('name', PaymentMethodEnum::BANK_TRANSFER),
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/payment::payment.method_name'))
                    ->attributes(['data-counter' => 400])
                    ->value(get_payment_setting(
                        'name',
                        PaymentMethodEnum::BANK_TRANSFER,
                        PaymentMethodEnum::BANK_TRANSFER()->label(),
                    )),
            )
            ->add(
                get_payment_setting_key('description', PaymentMethodEnum::BANK_TRANSFER),
                EditorField::class,
                EditorFieldOption::make()
                    ->wrapperAttributes(['style' => 'max-width: 99.8%'])
                    ->label(trans('plugins/payment::payment.payment_method_description'))
                    ->value(get_payment_setting('description', PaymentMethodEnum::BANK_TRANSFER))
            )
            ->paymentMethodLogoField(PaymentMethodEnum::BANK_TRANSFER)
            ->paymentFeeField(PaymentMethodEnum::BANK_TRANSFER)
            ->addAvailableCountriesField(PaymentMethodEnum::BANK_TRANSFER)
            ->when(
                apply_filters(PAYMENT_METHOD_SETTINGS_CONTENT, null, PaymentMethodEnum::BANK_TRANSFER),
                function (FormAbstract $form, ?string $data): void {
                    $form->add('metabox', HtmlField::class, ['html' => $data]);
                }
            );
    }
}
