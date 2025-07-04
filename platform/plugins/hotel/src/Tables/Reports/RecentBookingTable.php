<?php

namespace Guestcms\Hotel\Tables\Reports;

use Guestcms\Base\Facades\BaseHelper;
use Guestcms\Base\Facades\Html;
use Guestcms\Hotel\Models\Booking;
use Guestcms\Table\Abstracts\TableAbstract;
use Guestcms\Table\Columns\CreatedAtColumn;
use Guestcms\Table\Columns\FormattedColumn;
use Guestcms\Table\Columns\IdColumn;
use Guestcms\Table\Columns\StatusColumn;
use Illuminate\Database\Eloquent\Builder;

class RecentBookingTable extends TableAbstract
{
    public function setup(): void
    {
        $columns = [
            IdColumn::make(),
            FormattedColumn::make('customer_id')
                ->getValueUsing(function (FormattedColumn $column) {
                    $item = $column->getItem();

                    return $item->address->id ? BaseHelper::clean(
                        $item->address->first_name . ' ' . $item->address->last_name
                    ) : '&mdash;';
                })
                ->title(trans('plugins/hotel::booking.customer'))
                ->alignLeft()
                ->orderable(false)
                ->searchable(false),
            FormattedColumn::make('room_id')
                ->getValueUsing(function (FormattedColumn $column) {
                    $item = $column->getItem();

                    return $item->room->room->exists ? Html::link(
                        $item->room->room->url,
                        BaseHelper::clean($item->room->room->name),
                        ['target' => '_blank']
                    ) : $item->room->room_name;
                })
                ->title(trans('plugins/hotel::booking.room'))
                ->alignLeft()
                ->orderable(false)
                ->searchable(false),
            FormattedColumn::make('amount')
                ->getValueUsing(function (FormattedColumn $column) {
                    $item = $column->getItem();

                    return format_price($item->amount);
                })
                ->title(trans('plugins/hotel::booking.amount'))
                ->alignLeft(),
            FormattedColumn::make('booking_period')
                ->getValueUsing(function (FormattedColumn $column) {
                    $item = $column->getItem();

                    return BaseHelper::formatDate($item->room->start_date) . ' -> ' . BaseHelper::formatDate(
                        $item->room->end_date
                    );
                })
                ->title(trans('plugins/hotel::booking.booking_period'))
                ->orderable(false)
                ->searchable(false)
                ->alignLeft(),
            CreatedAtColumn::make(),
        ];

        if (is_plugin_active('payment')) {
            $columns = array_merge($columns, [
                FormattedColumn::make('payment_id')
                    ->getValueUsing(function (FormattedColumn $column) {
                        $item = $column->getItem();

                        return BaseHelper::clean($item->payment->payment_channel->label() ?: '&mdash;');
                    })
                    ->name('payment_id')
                    ->title(trans('plugins/hotel::booking.payment_method'))
                    ->alignLeft()
                    ->orderable(false)
                    ->searchable(false),
                FormattedColumn::make('payment_status')
                    ->getValueUsing(function (FormattedColumn $column) {
                        $item = $column->getItem();

                        return $item->payment->status->label() ? BaseHelper::clean(
                            $item->payment->status->toHtml()
                        ) : '&mdash;';
                    })
                    ->name('payment_status')
                    ->title(trans('plugins/hotel::booking.payment_status_label'))
                    ->orderable(false)
                    ->searchable(false),
            ]);
        }

        $this
            ->model(Booking::class)
            ->addColumns([...$columns, StatusColumn::make()])
            ->queryUsing(function (Builder $query) {
                $query = $query
                    ->select([
                        'id',
                        'customer_id',
                        'amount',
                        'created_at',
                        'status',
                        'payment_id',
                    ])
                    ->with(['room', 'address']);

                if (is_plugin_active('payment')) {
                    $query->with('payment');
                }

                if ($this->request->has('date_from') && $this->request->has('date_to')) {
                    $query->whereBetween('created_at', [
                        $this->request->input('date_from'),
                        $this->request->input('date_to'),
                    ]);
                }

                return $query;
            });

        $this->type = self::TABLE_TYPE_SIMPLE;
        $this->defaultSortColumn = 0;
        $this->view = $this->simpleTableView();
    }
}
