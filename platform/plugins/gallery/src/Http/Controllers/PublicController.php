<?php

namespace Guestcms\Gallery\Http\Controllers;

use Guestcms\Base\Http\Controllers\BaseController;
use Guestcms\Gallery\Facades\Gallery;
use Guestcms\Gallery\Models\Gallery as GalleryModel;
use Guestcms\SeoHelper\Facades\SeoHelper;
use Guestcms\Theme\Facades\Theme;

class PublicController extends BaseController
{
    public function getGalleries()
    {
        $galleries = GalleryModel::query()
            ->wherePublished()
            ->with(['slugable', 'user'])
            ->orderBy('order')->latest()
            ->get();

        SeoHelper::setTitle(__('Galleries'));

        Theme::breadcrumb()->add(__('Galleries'), Gallery::getGalleriesPageUrl());

        return Theme::scope('galleries', compact('galleries'), 'plugins/gallery::themes.galleries')
            ->render();
    }
}
