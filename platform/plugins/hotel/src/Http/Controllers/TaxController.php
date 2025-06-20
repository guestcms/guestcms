<?php

namespace Guestcms\Hotel\Http\Controllers;

use Guestcms\Base\Http\Actions\DeleteResourceAction;
use Guestcms\Base\Http\Controllers\BaseController;
use Guestcms\Hotel\Forms\TaxForm;
use Guestcms\Hotel\Http\Requests\TaxRequest;
use Guestcms\Hotel\Models\Tax;
use Guestcms\Hotel\Tables\TaxTable;

class TaxController extends BaseController
{
    public function __construct()
    {
        $this
            ->breadcrumb()
            ->add(trans('plugins/hotel::hotel.name'))
            ->add(trans('plugins/hotel::tax.name'), route('tax.index'));
    }

    public function index(TaxTable $dataTable)
    {
        $this->pageTitle(trans('plugins/hotel::tax.name'));

        return $dataTable->renderTable();
    }

    public function create()
    {
        $this->pageTitle(trans('plugins/hotel::tax.create'));

        return TaxForm::create()->renderForm();
    }

    public function store(TaxRequest $request)
    {
        $form = TaxForm::create();
        $form->save();

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('tax.index'))
            ->setNextUrl(route('tax.edit', $form->getModel()->getKey()))
            ->withCreatedSuccessMessage();
    }

    public function edit(Tax $tax)
    {
        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $tax->title]));

        return TaxForm::createFromModel($tax)->renderForm();
    }

    public function update(Tax $tax, TaxRequest $request)
    {
        TaxForm::createFromModel($tax)
            ->setRequest($request)
            ->save();

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('tax.index'))
            ->withUpdatedSuccessMessage();
    }

    public function destroy(Tax $tax)
    {
        return DeleteResourceAction::make($tax);
    }
}
