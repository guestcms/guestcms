<?php

namespace Guestcms\Hotel\Http\Controllers;

use Guestcms\Base\Http\Actions\DeleteResourceAction;
use Guestcms\Base\Http\Controllers\BaseController;
use Guestcms\Hotel\Forms\AmenityForm;
use Guestcms\Hotel\Http\Requests\AmenityRequest;
use Guestcms\Hotel\Models\Amenity;
use Guestcms\Hotel\Tables\AmenityTable;

class AmenityController extends BaseController
{
    public function __construct()
    {
        $this
            ->breadcrumb()
            ->add(trans('plugins/hotel::hotel.name'))
            ->add(trans('plugins/hotel::amenity.name'), route('amenity.index'));
    }

    public function index(AmenityTable $table)
    {
        $this->pageTitle(trans('plugins/hotel::amenity.name'));

        return $table->renderTable();
    }

    public function create()
    {
        $this->pageTitle(trans('plugins/hotel::amenity.create'));

        return AmenityForm::create()->renderForm();
    }

    public function store(AmenityRequest $request)
    {
        $form = AmenityForm::create();
        $form->save();

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('amenity.index'))
            ->setNextUrl(route('amenity.edit', $form->getModel()->getKey()))
            ->withCreatedSuccessMessage();
    }

    public function edit(Amenity $amenity)
    {
        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $amenity->name]));

        return AmenityForm::createFromModel($amenity)->renderForm();
    }

    public function update(Amenity $amenity, AmenityRequest $request)
    {
        AmenityForm::createFromModel($amenity)
            ->setRequest($request)
            ->save();

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('amenity.index'))
            ->withUpdatedSuccessMessage();
    }

    public function destroy(Amenity $amenity)
    {
        return DeleteResourceAction::make($amenity);
    }
}
