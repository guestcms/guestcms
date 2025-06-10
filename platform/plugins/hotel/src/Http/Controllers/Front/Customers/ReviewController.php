<?php

namespace Guestcms\Hotel\Http\Controllers\Front\Customers;

use Guestcms\Base\Http\Controllers\BaseController;
use Guestcms\Hotel\Models\Review;
use Guestcms\SeoHelper\Facades\SeoHelper;
use Guestcms\Theme\Facades\Theme;

class ReviewController extends BaseController
{
    public function __construct()
    {
        Theme::asset()
            ->add('customer-style', 'vendor/core/plugins/hotel/css/customer.css');

        Theme::asset()
            ->container('footer')
            ->add('utilities-js', 'vendor/core/plugins/hotel/js/utilities.js', ['jquery'])
            ->add('cropper-js', 'vendor/core/core/base/libraries/cropper.min.js', ['jquery'])
            ->add('avatar-js', 'vendor/core/plugins/hotel/js/avatar.js', ['jquery']);
    }
    public function index()
    {
        SeoHelper::setTitle(__('Reviews'));

        $reviews = Review::query()
            ->where([
                'customer_id' => auth('customer')->id(),
            ])
            ->with('room')
            ->orderByDesc('created_at')
            ->paginate(5);

        Theme::breadcrumb()
            ->add(__('Reviews'), route('customer.reviews'));

        return Theme::scope(
            'hotel.customers.reviews',
            compact('reviews'),
            'plugins/hotel::themes.customers.reviews'
        )->render();
    }
}
