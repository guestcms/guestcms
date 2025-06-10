<?php

namespace Guestcms\Hotel\Http\Controllers;

use Guestcms\Base\Facades\Assets;
use Guestcms\Base\Http\Controllers\BaseController;
use Guestcms\Base\Widgets\AdminWidget;
use Guestcms\Hotel\Facades\HotelHelper;
use Guestcms\Hotel\Tables\Reports\RecentBookingTable;
use Illuminate\Http\Request;

class BookingReportController extends BaseController
{
    public function index(Request $request, AdminWidget $widget)
    {
        $this->pageTitle(trans('plugins/hotel::booking.reports'));

        Assets::addScriptsDirectly([
            'vendor/core/plugins/hotel/libraries/daterangepicker/daterangepicker.js',
            'vendor/core/plugins/hotel/js/report.js',
        ])
            ->addStylesDirectly([
                'vendor/core/plugins/hotel/libraries/daterangepicker/daterangepicker.css',
            ])
            ->addScripts(['moment']);

        Assets::usingVueJS();

        [$startDate, $endDate] = HotelHelper::getDateRangeInReport($request);

        if ($request->ajax()) {
            return $this
                ->httpResponse()->setData(view('plugins/hotel::reports.ajax', compact('widget'))->render());
        }

        return view(
            'plugins/hotel::reports.index',
            compact('startDate', 'endDate', 'widget')
        );
    }

    public function getRecentBookings(RecentBookingTable $table)
    {
        return $table->renderTable();
    }
}
