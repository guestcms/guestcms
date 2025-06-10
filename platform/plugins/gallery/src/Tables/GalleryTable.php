<?php

namespace Guestcms\Gallery\Tables;

use Guestcms\Gallery\Models\Gallery;
use Guestcms\Table\Abstracts\TableAbstract;
use Guestcms\Table\Actions\DeleteAction;
use Guestcms\Table\Actions\EditAction;
use Guestcms\Table\BulkActions\DeleteBulkAction;
use Guestcms\Table\BulkChanges\CreatedAtBulkChange;
use Guestcms\Table\BulkChanges\NameBulkChange;
use Guestcms\Table\BulkChanges\StatusBulkChange;
use Guestcms\Table\Columns\Column;
use Guestcms\Table\Columns\CreatedAtColumn;
use Guestcms\Table\Columns\IdColumn;
use Guestcms\Table\Columns\ImageColumn;
use Guestcms\Table\Columns\NameColumn;
use Guestcms\Table\Columns\StatusColumn;
use Guestcms\Table\HeaderActions\CreateHeaderAction;
use Illuminate\Database\Eloquent\Builder;

class GalleryTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(Gallery::class)
            ->addHeaderAction(CreateHeaderAction::make()->route('galleries.create'))
            ->addColumns([
                IdColumn::make(),
                ImageColumn::make(),
                NameColumn::make()->route('galleries.edit'),
                Column::make('order')
                    ->title(trans('core/base::tables.order'))
                    ->width(100),
                CreatedAtColumn::make(),
                StatusColumn::make(),
            ])
            ->addActions([
                EditAction::make()->route('galleries.edit'),
                DeleteAction::make()->route('galleries.destroy'),
            ])
            ->addBulkActions([
                DeleteBulkAction::make()->permission('galleries.destroy'),
            ])
            ->addBulkChanges([
                NameBulkChange::make(),
                StatusBulkChange::make(),
                CreatedAtBulkChange::make(),
            ])
            ->queryUsing(function (Builder $query) {
                return $query
                    ->select([
                        'id',
                        'name',
                        'order',
                        'created_at',
                        'status',
                        'image',
                    ]);
            });
    }
}
