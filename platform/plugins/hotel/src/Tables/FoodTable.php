<?php

namespace Guestcms\Hotel\Tables;

use Guestcms\Base\Enums\BaseStatusEnum;
use Guestcms\Hotel\Models\Food;
use Guestcms\Hotel\Tables\Formatters\PriceFormatter;
use Guestcms\Table\Abstracts\TableAbstract;
use Guestcms\Table\Actions\DeleteAction;
use Guestcms\Table\Actions\EditAction;
use Guestcms\Table\BulkActions\DeleteBulkAction;
use Guestcms\Table\Columns\Column;
use Guestcms\Table\Columns\CreatedAtColumn;
use Guestcms\Table\Columns\IdColumn;
use Guestcms\Table\Columns\ImageColumn;
use Guestcms\Table\Columns\NameColumn;
use Guestcms\Table\Columns\StatusColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\JsonResponse;

class FoodTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(Food::class)
            ->addActions([
                EditAction::make()->route('food.edit'),
                DeleteAction::make()->route('food.destroy'),
            ]);
    }

    public function ajax(): JsonResponse
    {
        $data = $this->table
            ->eloquent($this->query())
            ->formatColumn('price', PriceFormatter::class);

        return $this->toJson($data);
    }

    public function query(): Relation|Builder|QueryBuilder
    {
        $query = $this
            ->getModel()
            ->query()
            ->select([
                'id',
                'name',
                'image',
                'price',
                'created_at',
                'status',
            ]);

        return $this->applyScopes($query);
    }

    public function columns(): array
    {
        return [
            IdColumn::make(),
            ImageColumn::make(),
            NameColumn::make()->route('food.edit'),
            Column::formatted('price')
                ->title(trans('plugins/hotel::food.form.price')),
            CreatedAtColumn::make(),
            StatusColumn::make(),
        ];
    }

    public function buttons(): array
    {
        return $this->addCreateButton(route('food.create'), 'food.create');
    }

    public function bulkActions(): array
    {
        return [
            DeleteBulkAction::make()->permission('food.destroy'),
        ];
    }

    public function getBulkChanges(): array
    {
        return [
            'name' => [
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
