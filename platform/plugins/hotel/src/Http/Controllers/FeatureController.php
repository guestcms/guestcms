<?php

namespace Guestcms\Hotel\Http\Controllers;

use Guestcms\Base\Http\Actions\DeleteResourceAction;
use Guestcms\Base\Http\Controllers\BaseController;
use Guestcms\Hotel\Forms\FeatureForm;
use Guestcms\Hotel\Http\Requests\FeatureRequest;
use Guestcms\Hotel\Models\Feature;
use Guestcms\Hotel\Tables\FeatureTable;

class FeatureController extends BaseController
{
    public function __construct()
    {
        $this
            ->breadcrumb()
            ->add(trans('plugins/hotel::hotel.name'))
            ->add(trans('plugins/hotel::feature.name'), route('feature.index'));
    }

    public function index(FeatureTable $table)
    {
        $this->pageTitle(trans('plugins/hotel::feature.name'));

        return $table->renderTable();
    }

    public function create()
    {
        $this->pageTitle(trans('plugins/hotel::feature.create'));

        return FeatureForm::create()->renderForm();
    }

    public function store(FeatureRequest $request)
    {
        $form = FeatureForm::create();
        $form->save();

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('feature.index'))
            ->setNextUrl(route('feature.edit', $form->getModel()->getKey()))
            ->withCreatedSuccessMessage();
    }

    public function edit(Feature $feature)
    {
        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $feature->name]));

        return FeatureForm::createFromModel($feature)->renderForm();
    }

    public function update(Feature $feature, FeatureRequest $request)
    {
        FeatureForm::createFromModel($feature)
            ->setRequest($request)
            ->save();

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('feature.index'))
            ->withUpdatedSuccessMessage();
    }

    public function destroy(Feature $feature)
    {
        return DeleteResourceAction::make($feature);
    }
}
