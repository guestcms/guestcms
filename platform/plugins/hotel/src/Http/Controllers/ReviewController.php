<?php

namespace Guestcms\Hotel\Http\Controllers;

use Guestcms\Base\Facades\Assets;
use Guestcms\Base\Http\Actions\DeleteResourceAction;
use Guestcms\Base\Http\Controllers\BaseController;
use Guestcms\Hotel\Models\Review;
use Guestcms\Hotel\Tables\ReviewTable;

class ReviewController extends BaseController
{
    public function __construct()
    {
        $this
            ->breadcrumb()
            ->add(trans('plugins/hotel::hotel.name'));
    }

    public function index(ReviewTable $dataTable)
    {
        $this->pageTitle(trans('plugins/hotel::review.name'));

        Assets::addStylesDirectly('vendor/core/plugins/hotel/css/review.css');

        return $dataTable->renderTable();
    }

    public function destroy(Review $review)
    {
        return DeleteResourceAction::make($review);
    }
}
