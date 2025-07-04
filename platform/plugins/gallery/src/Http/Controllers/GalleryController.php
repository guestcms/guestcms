<?php

namespace Guestcms\Gallery\Http\Controllers;

use Guestcms\Base\Http\Actions\DeleteResourceAction;
use Guestcms\Base\Http\Controllers\BaseController;
use Guestcms\Base\Supports\Breadcrumb;
use Guestcms\Gallery\Forms\GalleryForm;
use Guestcms\Gallery\Http\Requests\GalleryRequest;
use Guestcms\Gallery\Models\Gallery;
use Guestcms\Gallery\Tables\GalleryTable;
use Illuminate\Support\Facades\Auth;

class GalleryController extends BaseController
{
    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('plugins/gallery::gallery.galleries'), route('galleries.index'));
    }

    public function index(GalleryTable $dataTable)
    {
        $this->pageTitle(trans('plugins/gallery::gallery.galleries'));

        return $dataTable->renderTable();
    }

    public function create()
    {
        $this->pageTitle(trans('plugins/gallery::gallery.create'));

        return GalleryForm::create()->renderForm();
    }

    public function store(GalleryRequest $request)
    {
        $form = GalleryForm::create();

        $form->saving(function (GalleryForm $form) use ($request): void {
            $form
                ->getModel()
                ->fill([...$request->validated(), 'user_id' => Auth::guard()->id()])
                ->save();
        });

        return $this
            ->httpResponse()
            ->setPreviousRoute('galleries.index')
            ->setNextRoute('galleries.edit', $form->getModel()->getKey())
            ->withCreatedSuccessMessage();
    }

    public function edit(Gallery $gallery)
    {
        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $gallery->name]));

        return GalleryForm::createFromModel($gallery)->renderForm();
    }

    public function update(Gallery $gallery, GalleryRequest $request)
    {
        GalleryForm::createFromModel($gallery)->setRequest($request)->save();

        return $this
            ->httpResponse()
            ->setPreviousRoute('galleries.index')
            ->withUpdatedSuccessMessage();
    }

    public function destroy(Gallery $gallery)
    {
        return DeleteResourceAction::make($gallery);
    }
}
