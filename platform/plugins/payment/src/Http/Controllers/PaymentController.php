<?php

namespace Guestcms\Payment\Http\Controllers;

use Guestcms\Base\Facades\Assets;
use Guestcms\Base\Facades\PageTitle;
use Guestcms\Base\Http\Actions\DeleteResourceAction;
use Guestcms\Payment\Enums\PaymentStatusEnum;
use Guestcms\Payment\Forms\BankTransferPaymentMethodForm;
use Guestcms\Payment\Forms\CODPaymentMethodForm;
use Guestcms\Payment\Forms\Settings\PaymentMethodSettingForm;
use Guestcms\Payment\Http\Requests\PaymentMethodRequest;
use Guestcms\Payment\Http\Requests\Settings\PaymentMethodSettingRequest;
use Guestcms\Payment\Http\Requests\UpdatePaymentRequest;
use Guestcms\Payment\Models\Payment;
use Guestcms\Payment\Tables\PaymentTable;
use Guestcms\Setting\Http\Controllers\SettingController;
use Guestcms\Setting\Supports\SettingStore;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class PaymentController extends SettingController
{
    public function index(PaymentTable $table)
    {
        PageTitle::setTitle(trans('plugins/payment::payment.name'));

        return $table->renderTable();
    }

    public function destroy(Payment $payment)
    {
        return DeleteResourceAction::make($payment);
    }

    public function show(Payment $payment)
    {
        PageTitle::setTitle(trans('plugins/payment::payment.view_transaction', ['charge_id' => $payment->charge_id]));

        $detail = apply_filters(PAYMENT_FILTER_PAYMENT_INFO_DETAIL, null, $payment);

        $paymentStatuses = PaymentStatusEnum::labels();

        if ($payment->status != PaymentStatusEnum::PENDING) {
            Arr::forget($paymentStatuses, PaymentStatusEnum::PENDING);
        }

        Assets::addScriptsDirectly('vendor/core/plugins/payment/js/payment-detail.js');

        return view('plugins/payment::show', compact('payment', 'detail', 'paymentStatuses'));
    }

    public function methods()
    {
        PageTitle::setTitle(trans('plugins/payment::payment.payment_methods'));

        Assets::addScriptsDirectly('vendor/core/plugins/payment/js/payment-methods.js');

        $form = PaymentMethodSettingForm::create();
        $codForm = CODPaymentMethodForm::create();
        $bankTransferForm = BankTransferPaymentMethodForm::create();

        return view(
            'plugins/payment::settings.index',
            compact('form', 'codForm', 'bankTransferForm')
        );
    }

    public function updateSettings(PaymentMethodSettingRequest $request)
    {
        return $this->performUpdate($request->validated());
    }

    public function updateMethods(PaymentMethodRequest $request)
    {
        $data = Arr::except($request->input(), ['_token', 'type']);

        $data[get_payment_setting_key('status', $request->input('type'))] = 1;

        return $this->performUpdate($data);
    }

    public function updateMethodStatus(Request $request, SettingStore $settingStore)
    {
        $settingStore
            ->set('payment_' . $request->input('type') . '_status', 0)
            ->save();

        return $this
            ->httpResponse()
            ->setMessage(trans('plugins/payment::payment.turn_off_success'));
    }

    public function update(Payment $payment, UpdatePaymentRequest $request)
    {
        $payment->status = $request->input('status');
        $payment->save();

        do_action(ACTION_AFTER_UPDATE_PAYMENT, $request, $payment);

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('payment.index'))
            ->withUpdatedSuccessMessage();
    }

    public function getRefundDetail(int|string $id, int|string $refundId)
    {
        $payment = Payment::query()->findOrFail($id);

        $data = [];

        $data = apply_filters(PAYMENT_FILTER_GET_REFUND_DETAIL, $data, $payment, $refundId);

        if (! Arr::get($data, 'error') && Arr::get($data, 'data', [])) {
            $metadata = $payment->metadata;
            $refunds = Arr::get($metadata, 'refunds', []);
            if ($refunds) {
                foreach ($refunds as $key => $refund) {
                    if (Arr::get($refund, '_refund_id') == $refundId) {
                        $refunds[$key] = array_merge($refunds[$key], (array) Arr::get($data, 'data'));
                    }
                }

                Arr::set($metadata, 'refunds', $refunds);
                $payment->metadata = $metadata;
                $payment->save();
            }
        }

        $view = Arr::get($data, 'view');

        $response = $this->httpResponse();

        if ($view) {
            $response->setData($view);
        }

        return $response
            ->setError((bool) Arr::get($data, 'error'))
            ->setMessage(Arr::get($data, 'message', ''));
    }
}
