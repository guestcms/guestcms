<?php

namespace Guestcms\Hotel\Http\Controllers;

use Guestcms\Base\Http\Actions\DeleteResourceAction;
use Guestcms\Base\Http\Controllers\BaseController;
use Guestcms\Hotel\Forms\FoodForm;
use Guestcms\Hotel\Http\Requests\FoodRequest;
use Guestcms\Hotel\Models\Food;
use Guestcms\Hotel\Tables\FoodTable;

class FoodController extends BaseController
{
    public function __construct()
    {
        $this
            ->breadcrumb()
            ->add(trans('plugins/hotel::hotel.name'))
            ->add(trans('plugins/hotel::food.name'), route('food.index'));
    }

    public function index(FoodTable $table)
    {
        $this->pageTitle(trans('plugins/hotel::food.name'));

        return $table->renderTable();
    }

    public function create()
    {
        $this->pageTitle(trans('plugins/hotel::food.create'));

        return FoodForm::create()->renderForm();
    }

    public function store(FoodRequest $request)
    {
        $form = FoodForm::create();
        $form->save();

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('food.index'))
            ->setNextUrl(route('food.edit', $form->getModel()->getKey()))
            ->withCreatedSuccessMessage();
    }

    public function edit(Food $food)
    {
        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $food->name]));

        return FoodForm::createFromModel($food)->renderForm();
    }

    public function update(Food $food, FoodRequest $request)
    {
        FoodForm::createFromModel($food)
            ->setRequest($request)
            ->save();

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('food.index'))
            ->withUpdatedSuccessMessage();
    }

    public function destroy(Food $food)
    {
        return DeleteResourceAction::make($food);
    }
}
