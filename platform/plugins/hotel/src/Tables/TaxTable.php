<?php

namespace Guestcms\Hotel\Tables;

use Guestcms\Base\Enums\BaseStatusEnum;
use Guestcms\Hotel\Models\Tax;
use Guestcms\Table\Abstracts\TableAbstract;
use Guestcms\Table\Actions\DeleteAction;
use Guestcms\Table\Actions\EditAction;
use Guestcms\Table\BulkActions\DeleteBulkAction;
use Guestcms\Table\Columns\Column;
use Guestcms\Table\Columns\CreatedAtColumn;
use Guestcms\Table\Columns\IdColumn;
use Guestcms\Table\Columns\NameColumn;
use Guestcms\Table\Columns\StatusColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\JsonResponse;

class TaxTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(Tax::class)
            ->addActions([
                EditAction::make()->route('tax.edit'),
                DeleteAction::make()->route('tax.destroy'),
            ]);
    }

    public function ajax(): JsonResponse
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('percentage', function ($item) {
                return $item->percentage . '%';
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
                'title',
                'percentage',
                'priority',
                'status',
                'created_at',
            ]);

        return $this->applyScopes($query);
    }

    public function columns(): array
    {
        return [
            IdColumn::make(),
            NameColumn::make('title')->route('tax.edit'),
            Column::make('percentage')
                ->title(trans('plugins/hotel::tax.percentage')),
            Column::make('priority')
                ->title(trans('plugins/hotel::tax.priority')),
            CreatedAtColumn::make(),
            StatusColumn::make(),
        ];
    }

    public function buttons(): array
    {
        return $this->addCreateButton(route('tax.create'), 'tax.create');
    }

    public function bulkActions(): array
    {
        return [
            DeleteBulkAction::make()->permission('tax.destroy'),
        ];
    }

    public function getBulkChanges(): array
    {
        return [
            'title' => [
                'title' => trans('core/base::tables.name'),
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
