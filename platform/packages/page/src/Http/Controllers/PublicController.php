<?php

namespace Guestcms\Page\Http\Controllers;

use Guestcms\Base\Http\Controllers\BaseController;
use Guestcms\Page\Models\Page;
use Guestcms\Page\Services\PageService;
use Guestcms\Slug\Facades\SlugHelper;
use Guestcms\Theme\Events\RenderingSingleEvent;
use Guestcms\Theme\Facades\Theme;

class PublicController extends BaseController
{
    public function getPage(string $slug, PageService $pageService)
    {
        $slug = SlugHelper::getSlug($slug, SlugHelper::getPrefix(Page::class));

        abort_unless($slug, 404);

        $data = $pageService->handleFrontRoutes($slug);

        if (isset($data['slug']) && $data['slug'] !== $slug->key) {
            return redirect()->to(url(SlugHelper::getPrefix(Page::class) . '/' . $data['slug']));
        }

        event(new RenderingSingleEvent($slug));

        return Theme::scope($data['view'], $data['data'], $data['default_view'])->render();
    }
}
