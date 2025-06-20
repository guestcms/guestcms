<?php

namespace Guestcms\Hotel\Http\Controllers;

use Guestcms\Base\Facades\Assets;
use Guestcms\Base\Http\Actions\DeleteResourceAction;
use Guestcms\Base\Http\Controllers\BaseController;
use Guestcms\Hotel\Forms\CustomerForm;
use Guestcms\Hotel\Http\Requests\CustomerCreateRequest;
use Guestcms\Hotel\Http\Requests\CustomerEditRequest;
use Guestcms\Hotel\Models\Customer;
use Guestcms\Hotel\Tables\CustomerTable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;

class CustomerController extends BaseController
{
    public function __construct()
    {
        $this
            ->breadcrumb()
            ->add(trans('plugins/hotel::hotel.name'))
            ->add(trans('plugins/hotel::customer.name'), route('customer.index'));
    }

    public function index(CustomerTable $table)
    {
        $this->pageTitle(trans('plugins/hotel::customer.name'));

        return $table->renderTable();
    }

    public function create()
    {
        $this->pageTitle(trans('plugins/hotel::customer.create'));

        return CustomerForm::create()->renderForm();
    }

    public function store(CustomerCreateRequest $request)
    {
        $form = CustomerForm::create();
        $form->saving(function (CustomerForm $form) use ($request): void {
            $form
                ->getModel()
                ->fill([
                    ...$request->validated(),
                    'password' => Hash::make($request->input('password')),
                ])
                ->save();
        });

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('customer.index'))
            ->setNextUrl(route('customer.edit', $form->getModel()->getKey()))
            ->withCreatedSuccessMessage();
    }

    public function edit(Customer $customer)
    {
        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $customer->name]));

        Assets::addScriptsDirectly('vendor/core/plugins/hotel/js/customer.js');

        $customer->password = null;

        return CustomerForm::createFromModel($customer)->renderForm();
    }

    public function update(Customer $customer, CustomerEditRequest $request)
    {
        CustomerForm::createFromModel($customer)
            ->saving(function (CustomerForm $form) use ($request): void {
                $data = Arr::except($request->validated(), 'password');

                if ($request->input('is_change_password') == 1) {
                    $data['password'] = Hash::make($request->input('password'));
                }

                $form
                    ->getModel()
                    ->fill($data)
                    ->save();
            });

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('customer.index'))
            ->withUpdatedSuccessMessage();
    }

    public function destroy(Customer $customer)
    {
        return DeleteResourceAction::make($customer);
    }
}
