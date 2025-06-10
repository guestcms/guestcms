<?php

namespace Guestcms\Blog\Tables;

use Guestcms\Blog\Models\Tag;
use Guestcms\Table\Abstracts\TableAbstract;
use Guestcms\Table\Actions\DeleteAction;
use Guestcms\Table\Actions\EditAction;
use Guestcms\Table\BulkActions\DeleteBulkAction;
use Guestcms\Table\BulkChanges\CreatedAtBulkChange;
use Guestcms\Table\BulkChanges\NameBulkChange;
use Guestcms\Table\BulkChanges\StatusBulkChange;
use Guestcms\Table\Columns\CreatedAtColumn;
use Guestcms\Table\Columns\IdColumn;
use Guestcms\Table\Columns\NameColumn;
use Guestcms\Table\Columns\StatusColumn;
use Guestcms\Table\HeaderActions\CreateHeaderAction;
use Illuminate\Database\Eloquent\Builder;

class TagTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(Tag::class)
            ->addHeaderAction(CreateHeaderAction::make()->route('tags.create'))
            ->addColumns([
                IdColumn::make(),
                NameColumn::make()->route('tags.edit'),
                CreatedAtColumn::make(),
                StatusColumn::make(),
            ])
            ->addActions([
                EditAction::make()->route('tags.edit'),
                DeleteAction::make()->route('tags.destroy'),
            ])
            ->addBulkActions([
                DeleteBulkAction::make()->permission('tags.destroy'),
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
                        'created_at',
                        'status',
                    ]);
            });
    }
}
