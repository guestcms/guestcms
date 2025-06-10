<?php

namespace Guestcms\Blog\Http\Controllers;

use Guestcms\Base\Facades\BaseHelper;
use Guestcms\Base\Http\Controllers\BaseController;
use Guestcms\Blog\Repositories\Interfaces\PostInterface;
use Guestcms\SeoHelper\Facades\SeoHelper;
use Guestcms\Theme\Facades\Theme;
use Illuminate\Http\Request;

class PublicController extends BaseController
{
    public function getSearch(Request $request, PostInterface $postRepository)
    {
        $query = BaseHelper::stringify($request->input('q'));

        $title = __('Search result for: ":query"', compact('query'));

        SeoHelper::setTitle($title)
            ->setDescription($title);

        $posts = $postRepository->getSearch($query, 0, (int) theme_option('number_of_posts_in_a_category', 12));

        Theme::breadcrumb()->add($title, route('public.search'));

        return Theme::scope('search', compact('posts'))
            ->render();
    }
}
