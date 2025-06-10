<?php

namespace Guestcms\Hotel\Tables;

use Guestcms\Base\Facades\BaseHelper;
use Guestcms\Base\Facades\Html;
use Guestcms\Hotel\Enums\ReviewStatusEnum;
use Guestcms\Hotel\Models\Review;
use Guestcms\Table\Abstracts\TableAbstract;
use Guestcms\Table\Actions\DeleteAction;
use Guestcms\Table\BulkActions\DeleteBulkAction;
use Guestcms\Table\Columns\Column;
use Guestcms\Table\Columns\CreatedAtColumn;
use Guestcms\Table\Columns\IdColumn;
use Guestcms\Table\Columns\StatusColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ReviewTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(Review::class)
            ->addActions([
                DeleteAction::make()->route('review.destroy'),
            ]);
    }

    public function ajax(): JsonResponse
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('customer_id', function (Review $item) {
                if (! $item->customer_id || ! $item->author?->id) {
                    return '&mdash;';
                }

                return Html::link(route('customer.edit', $item->author->id), BaseHelper::clean($item->author->name))->toHtml();
            })
            ->editColumn('room_id', function (Review $item) {
                if (! $item->room_id) {
                    return '&mdash;';
                }

                return Html::link(route('room.edit', $item->room_id), BaseHelper::clean($item->room->name))->toHtml();
            })
            ->editColumn('star', function (Review $item) {
                return view('plugins/hotel::partials.review-star', ['star' => $item->star])->render();
            })
            ->editColumn('content', function (Review $item) {
                return BaseHelper::clean($item->content);
            })
            ->filter(function ($query) {
                $keyword = $this->request->input('search.value');
                if ($keyword) {
                    return $query
                        ->orWhereHas('author', function ($subQuery) use ($keyword) {
                            return $subQuery
                                ->where('first_name', 'LIKE', '%' . $keyword . '%')
                                ->orWhere('last_name', 'LIKE', '%' . $keyword . '%')
                                ->orWhere(DB::raw('CONCAT(first_name, " ", last_name)'), 'LIKE', '%' . $keyword . '%');
                        });
                }

                return $query;
            });

        return $this->toJson($data);
    }

    public function query(): Relation|Builder|QueryBuilder
    {
        $query = $this
            ->getModel()
            ->query()
            ->select([
                'id',
                'room_id',
                'star',
                'content',
                'customer_id',
                'status',
                'created_at',
            ])
            ->with(['author']);

        return $this->applyScopes($query);
    }

    public function columns(): array
    {
        return [
            IdColumn::make(),
            Column::make('customer_id')
                ->title(trans('plugins/hotel::review.author'))
                ->alignLeft(),
            Column::make('room_id')
                ->title(trans('plugins/hotel::review.room_id'))
                ->alignLeft()
                ->orderable(false)
                ->searchable(false),
            Column::make('star')
                ->title(trans('plugins/hotel::review.star')),
            Column::make('content')
                ->title(trans('plugins/hotel::review.content')),
            CreatedAtColumn::make(),
            StatusColumn::make(),
        ];
    }

    public function bulkActions(): array
    {
        return [
            DeleteBulkAction::make()->permission('review.destroy'),
        ];
    }

    public function getBulkChanges(): array
    {
        return [
            'status' => [
                'title' => trans('core/base::tables.status'),
                'type' => 'select',
                'choices' => ReviewStatusEnum::labels(),
                'validate' => 'required|in:' . implode(',', ReviewStatusEnum::values()),
            ],
            'created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'type' => 'datePicker',
            ],
        ];
    }
}
