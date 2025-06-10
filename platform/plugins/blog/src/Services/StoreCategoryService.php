<?php

namespace Guestcms\Blog\Services;

use Guestcms\Blog\Models\Post;
use Guestcms\Blog\Services\Abstracts\StoreCategoryServiceAbstract;
use Illuminate\Http\Request;

class StoreCategoryService extends StoreCategoryServiceAbstract
{
    public function execute(Request $request, Post $post): void
    {
        $post->categories()->sync($request->input('categories', []));
    }
}
