<?php

namespace Guestcms\Blog\Http\Controllers;

use Guestcms\Base\Http\Actions\DeleteResourceAction;
use Guestcms\Base\Http\Controllers\BaseController;
use Guestcms\Base\Http\Responses\BaseHttpResponse;
use Guestcms\Base\Supports\Breadcrumb;
use Guestcms\Blog\Forms\PostForm;
use Guestcms\Blog\Http\Requests\PostRequest;
use Guestcms\Blog\Models\Post;
use Guestcms\Blog\Services\StoreCategoryService;
use Guestcms\Blog\Services\StoreTagService;
use Guestcms\Blog\Tables\PostTable;
use Illuminate\Http\Request;

class PostController extends BaseController
{
    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('plugins/blog::base.menu_name'))
            ->add(trans('plugins/blog::posts.menu_name'), route('posts.index'));
    }

    public function index(PostTable $dataTable)
    {
        $this->pageTitle(trans('plugins/blog::posts.menu_name'));

        return $dataTable->renderTable();
    }

    public function create()
    {
        $this->pageTitle(trans('plugins/blog::posts.create'));

        return PostForm::create()->renderForm();
    }

    public function store(
        PostRequest $request,
        StoreTagService $tagService,
        StoreCategoryService $categoryService
    ) {
        $form = PostForm::create()->setRequest($request)->save();

        $post = $form->getModel();

        $tagService->execute($request, $post);

        $categoryService->execute($request, $post);

        return $this
            ->httpResponse()
            ->setPreviousRoute('posts.index')
            ->setNextRoute('posts.edit', $post->getKey())
            ->withCreatedSuccessMessage();
    }

    public function edit(Post $post)
    {
        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $post->name]));

        return PostForm::createFromModel($post)->renderForm();
    }

    public function update(
        Post $post,
        PostRequest $request,
        StoreTagService $tagService,
        StoreCategoryService $categoryService,
    ) {
        $form = PostForm::createFromModel($post)
            ->setRequest($request)
            ->save();

        /**
         * @var Post $post
         */
        $post = $form->getModel();

        $tagService->execute($request, $post);

        $categoryService->execute($request, $post);

        return $this
            ->httpResponse()
            ->setPreviousRoute('posts.index')
            ->withUpdatedSuccessMessage();
    }

    public function destroy(Post $post): DeleteResourceAction
    {
        return DeleteResourceAction::make($post);
    }

    public function getWidgetRecentPosts(Request $request): BaseHttpResponse
    {
        $limit = $request->integer('paginate', 10);
        $limit = $limit > 0 ? $limit : 10;

        $posts = Post::query()
            ->with(['slugable'])
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();

        return $this
            ->httpResponse()
            ->setData(view('plugins/blog::widgets.posts', compact('posts', 'limit'))->render());
    }
}
