<?php

namespace Guestcms\Hotel\Http\Controllers\Settings;

use Guestcms\Hotel\Forms\Settings\InvoiceSettingForm;
use Guestcms\Hotel\Http\Requests\Settings\InvoiceSettingRequest;
use Guestcms\Setting\Http\Controllers\SettingController;

class InvoiceSettingController extends SettingController
{
    public function edit()
    {
        $this->pageTitle(trans('plugins/hotel::settings.invoice.title'));

        return InvoiceSettingForm::create()->renderForm();
    }

    public function update(InvoiceSettingRequest $request)
    {
        return $this->performUpdate($request->validated());
    }
}
