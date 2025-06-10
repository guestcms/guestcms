<x-plugins-payment::payment-method :name="\Guestcms\Payment\Enums\PaymentMethodEnum::COD"
    :label="get_payment_setting('name', 'cod', trans('plugins/payment::payment.payment_via_cod'))" />