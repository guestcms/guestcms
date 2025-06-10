<?php

namespace Guestcms\Hotel\Tables;

use Guestcms\Base\Enums\BaseStatusEnum;
use Guestcms\Base\Facades\Html;
use Guestcms\Hotel\Models\Invoice;
use Guestcms\Hotel\Tables\Formatters\PriceFormatter;
use Guestcms\Table\Abstracts\TableAbstract;
use Guestcms\Table\Actions\DeleteAction;
use Guestcms\Table\Actions\EditAction;
use Guestcms\Table\BulkActions\DeleteBulkAction;
use Guestcms\Table\Columns\Column;
use Guestcms\Table\Columns\CreatedAtColumn;
use Guestcms\Table\Columns\IdColumn;
use Guestcms\Table\Columns\LinkableColumn;
use Guestcms\Table\Columns\StatusColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\JsonResponse;

class InvoiceTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(Invoice::class)
            ->addActions([
                EditAction::make()->route('invoices.show')->permission('invoices.edit'),
                DeleteAction::make()->route('invoices.destroy'),
            ]);
    }

    public function ajax(): JsonResponse
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('customer_name', function (Invoice $item) {
                if (! $item->customer_id || ! $item->customer) {
                    return $item->customer_name;
                }

                return Html::link(route('customer.edit', $item->customer), $item->customer->name);
            })
            ->formatColumn('amount', PriceFormatter::class);

        return $this->toJson($data);
    }

    public function query(): Relation|Builder|QueryBuilder
    {
        $query = $this
            ->getModel()
            ->query()
            ->select([
                'id',
                'customer_id',
                'customer_name',
                'code',
                'amount',
                'created_at',
                'status',
            ])
            ->with('customer');

        return $this->applyScopes($query);
    }

    public function columns(): array
    {
        return [
            IdColumn::make(),
            Column::make('customer_name')
                ->title(trans('plugins/hotel::invoice.customer'))
                ->alignLeft(),
            LinkableColumn::make('code')
                ->title(trans('plugins/hotel::invoice.code'))
                ->route('invoices.show')
                ->permission('invoices.edit')
                ->alignLeft(),
            Column::formatted('amount')
                ->title(trans('plugins/hotel::invoice.amount'))
                ->alignLeft(),
            CreatedAtColumn::make(),
            StatusColumn::make(),
        ];
    }

    public function bulkActions(): array
    {
        return [
            DeleteBulkAction::make()->permission('invoices.destroy'),
        ];
    }

    public function getBulkChanges(): array
    {
        return [
            'customer_name' => [
                'title' => trans('plugins/hotel::invoice.customer'),
                'type' => 'text',
                'validate' => 'required|max:120',
            ],
            'status' => [
                'title' => trans('core/base::tables.status'),
                'type' => 'select',
                'choices' => BaseStatusEnum::labels(),
                'validate' => 'required|in:' . implode(',', BaseStatusEnum::values()),
            ],
            'created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'type' => 'datePicker',
            ],
        ];
    }
}
