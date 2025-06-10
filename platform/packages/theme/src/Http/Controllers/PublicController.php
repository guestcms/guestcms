<?php

namespace Guestcms\Theme\Http\Controllers;

use Guestcms\Base\Facades\BaseHelper;
use Guestcms\Base\Http\Controllers\BaseController;
use Guestcms\Base\Http\Responses\BaseHttpResponse;
use Guestcms\Page\Models\Page;
use Guestcms\Page\Services\PageService;
use Guestcms\SeoHelper\Facades\SeoHelper;
use Guestcms\Slug\Facades\SlugHelper;
use Guestcms\Slug\Models\Slug;
use Guestcms\Theme\Events\RenderingHomePageEvent;
use Guestcms\Theme\Events\RenderingSingleEvent;
use Guestcms\Theme\Events\RenderingSiteMapEvent;
use Guestcms\Theme\Facades\SiteMapManager;
use Guestcms\Theme\Facades\Theme;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class PublicController extends BaseController
{
    public function getIndex()
    {
        Theme::addBodyAttributes(['id' => 'page-home']);

        if (defined('PAGE_MODULE_SCREEN_NAME') && BaseHelper::getHomepageId()) {
            $data = (new PageService())->handleFrontRoutes(null);

            event(new RenderingSingleEvent(new Slug()));

            if ($data) {
                return Theme::scope($data['view'], $data['data'], $data['default_view'])->render();
            }
        }

        SeoHelper::setTitle(Theme::getSiteTitle());

        event(RenderingHomePageEvent::class);

        return Theme::scope('index')->render();
    }

    public function getView(?string $key = null, string $prefix = '')
    {
        if (empty($key)) {
            return $this->getIndex();
        }

        $slug = SlugHelper::getSlug($key, $prefix);

        abort_unless($slug, 404);

        if (
            defined('PAGE_MODULE_SCREEN_NAME') &&
            $slug->reference_type === Page::class &&
            BaseHelper::isHomepage($slug->reference_id)
        ) {
            return redirect()->to(BaseHelper::getHomepageUrl());
        }

        $result = apply_filters(BASE_FILTER_PUBLIC_SINGLE_DATA, $slug);

        $extension = SlugHelper::getPublicSingleEndingURL();

        if ($extension) {
            $key = Str::replaceLast($extension, '', $key);
        }

        if ($result instanceof BaseHttpResponse) {
            return $result;
        }

        if (isset($result['slug']) && $result['slug'] !== $key) {
            $prefix = SlugHelper::getPrefix(Arr::first($result['data'])::class);

            return redirect()->route('public.single', empty($prefix) ? $result['slug'] : "$prefix/{$result['slug']}");
        }

        event(new RenderingSingleEvent($slug));

        if (! empty($result) && is_array($result)) {
            if (isset($result['view'])) {
                Theme::addBodyAttributes(['id' => Str::slug(Str::snake(Str::afterLast($slug->reference_type, '\\'))) . '-' . $slug->reference_id]);

                return Theme::scope($result['view'], $result['data'], Arr::get($result, 'default_view'))->render();
            }

            return $result;
        }

        abort(404);
    }

    public function getSiteMap()
    {
        return $this->getSiteMapIndex();
    }

    public function getSiteMapIndex(string $key = null, string $extension = 'xml')
    {
        if ($key == 'sitemap') {
            $key = null;
        }

        if (! SiteMapManager::init($key, $extension)->isCached()) {
            event(new RenderingSiteMapEvent($key));
        }

        // show your site map (options: 'xml' (default), 'xml-mobile', 'html', 'txt', 'ror-rss', 'ror-rdf', 'google-news')
        return SiteMapManager::render($key ? $extension : 'sitemapindex');
    }

    public function getViewWithPrefix(string $prefix, ?string $slug = null)
    {
        return $this->getView($slug, $prefix);
    }
}
