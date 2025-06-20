<?php

namespace Guestcms\Page\Services;

use Guestcms\Base\Enums\BaseStatusEnum;
use Guestcms\Base\Facades\BaseHelper;
use Guestcms\Base\Supports\RepositoryHelper;
use Guestcms\Media\Facades\RvMedia;
use Guestcms\Page\Models\Page;
use Guestcms\SeoHelper\Facades\SeoHelper;
use Guestcms\Slug\Models\Slug;
use Guestcms\Theme\Facades\Theme;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class PageService
{
    public function handleFrontRoutes(Slug|array|null $slug): Slug|array
    {
        if ($slug && (! $slug instanceof Slug || $slug->reference_type !== Page::class)) {
            return $slug;
        }

        $condition = [
            'id' => $slug ? $slug->reference_id : BaseHelper::getHomepageId(),
            'status' => BaseStatusEnum::PUBLISHED,
        ];

        if (Auth::guard()->check() && request()->input('preview')) {
            Arr::forget($condition, 'status');
        }

        $page = Page::query()
            ->where($condition)
            ->with('slugable');

        $page = RepositoryHelper::applyBeforeExecuteQuery($page, new Page(), true)->first();

        if (empty($page)) {
            if (! $slug || $slug->reference_id == BaseHelper::getHomepageId()) {
                return [];
            }

            abort(404);
        }

        if (! BaseHelper::isHomepage($page->getKey())) {
            SeoHelper::setTitle($page->name)
                ->setDescription($page->description);

            Theme::breadcrumb()->add($page->name, $page->url);
        } else {
            $siteTitle = theme_option('seo_title') ?: Theme::getSiteTitle();
            $seoDescription = theme_option('seo_description');

            SeoHelper::setTitle($siteTitle)
                ->setDescription($seoDescription);
        }

        if ($page->image) {
            SeoHelper::openGraph()->setImage(RvMedia::getImageUrl($page->image));
        }

        SeoHelper::openGraph()->setUrl($page->url);
        SeoHelper::openGraph()->setType('article');

        SeoHelper::meta()->setUrl($page->url);

        if ($page->template) {
            Theme::uses(Theme::getThemeName())
                ->layout($page->template);
        }

        if (function_exists('admin_bar')) {
            admin_bar()
                ->registerLink(
                    trans('packages/page::pages.edit_this_page'),
                    route('pages.edit', $page->getKey()),
                    null,
                    'pages.edit'
                );
        }

        if (function_exists('shortcode')) {
            shortcode()->getCompiler()->setEditLink(route('pages.edit', $page->getKey()), 'pages.edit');
        }

        do_action(BASE_ACTION_PUBLIC_RENDER_SINGLE, PAGE_MODULE_SCREEN_NAME, $page);

        return [
            'view' => 'page',
            'default_view' => 'packages/page::themes.page',
            'data' => compact('page'),
            'slug' => $page->slug,
        ];
    }
}
