<?php

namespace Guestcms\Contact\Http\Controllers;

use Guestcms\Base\Http\Actions\DeleteResourceAction;
use Guestcms\Base\Http\Controllers\BaseController;
use Guestcms\Base\Supports\Breadcrumb;
use Guestcms\Contact\Forms\CustomFieldForm;
use Guestcms\Contact\Http\Requests\CustomFieldRequest;
use Guestcms\Contact\Models\CustomField;
use Guestcms\Contact\Tables\CustomFieldTable;

class CustomFieldController extends BaseController
{
    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('plugins/contact::contact.name'))
            ->add(trans('plugins/contact::contact.custom_field.name'), route('contacts.custom-fields.index'));
    }

    public function index(CustomFieldTable $table)
    {
        $this->pageTitle(trans('plugins/contact::contact.custom_field.name'));

        return $table->renderTable();
    }

    public function create(): string
    {
        $this->pageTitle(trans('plugins/contact::contact.custom_field.create'));

        return CustomFieldForm::create()->renderForm();
    }

    public function store(CustomFieldRequest $request)
    {
        $form = CustomFieldForm::create();
        $form->setRequest($request)->saveOnlyValidatedData();

        if (! empty($options = $request->input('options', []))) {
            /**
             * @var CustomField $model
             */
            $model = $form->getModel();

            $model->saveOptions($options);
        }

        return $this
            ->httpResponse()
            ->setNextUrl(route('contacts.custom-fields.index'))
            ->withCreatedSuccessMessage();
    }

    public function edit(CustomField $customField): string
    {
        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $customField->name]));

        return CustomFieldForm::createFromModel($customField)->renderForm();
    }

    public function update(CustomField $customField, CustomFieldRequest $request)
    {
        CustomFieldForm::createFromModel($customField)->setRequest($request)->saveOnlyValidatedData();

        if (! empty($options = $request->input('options', []))) {
            $customField->saveOptions($options);
        }

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('contacts.custom-fields.index'))
            ->withUpdatedSuccessMessage();
    }

    public function destroy(CustomField $customField)
    {
        return DeleteResourceAction::make($customField);
    }
}
