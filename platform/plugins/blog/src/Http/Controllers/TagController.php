<?php

namespace Guestcms\Blog\Http\Controllers;

use Guestcms\ACL\Models\User;
use Guestcms\Base\Http\Actions\DeleteResourceAction;
use Guestcms\Base\Http\Controllers\BaseController;
use Guestcms\Base\Supports\Breadcrumb;
use Guestcms\Blog\Forms\TagForm;
use Guestcms\Blog\Http\Requests\TagRequest;
use Guestcms\Blog\Models\Tag;
use Guestcms\Blog\Tables\TagTable;
use Illuminate\Support\Facades\Auth;

class TagController extends BaseController
{
    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('plugins/blog::base.menu_name'))
            ->add(trans('plugins/blog::tags.menu'), route('tags.index'));
    }

    public function index(TagTable $dataTable)
    {
        $this->pageTitle(trans('plugins/blog::tags.menu'));

        return $dataTable->renderTable();
    }

    public function create()
    {
        $this->pageTitle(trans('plugins/blog::tags.create'));

        return TagForm::create()->renderForm();
    }

    public function store(TagRequest $request)
    {
        $form = TagForm::create();

        $form
            ->saving(function (TagForm $form) use ($request): void {
                $form
                    ->getModel()
                    ->fill([...$request->validated(),
                        'author_id' => Auth::guard()->id(),
                        'author_type' => User::class,
                    ])
                    ->save();
            });

        return $this
            ->httpResponse()
            ->setPreviousRoute('tags.index')
            ->setNextRoute('tags.edit', $form->getModel()->getKey())
            ->withCreatedSuccessMessage();
    }

    public function edit(Tag $tag)
    {
        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $tag->name]));

        return TagForm::createFromModel($tag)->renderForm();
    }

    public function update(Tag $tag, TagRequest $request)
    {
        TagForm::createFromModel($tag)->setRequest($request)->save();

        return $this
            ->httpResponse()
            ->setPreviousRoute('tags.index')
            ->withUpdatedSuccessMessage();
    }

    public function destroy(Tag $tag)
    {
        return DeleteResourceAction::make($tag);
    }

    public function getAllTags()
    {
        return Tag::query()->pluck('name')->all();
    }
}
