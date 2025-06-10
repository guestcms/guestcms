<?php

namespace Guestcms\Newsletter\Tables;

use Guestcms\Newsletter\Models\Newsletter;
use Guestcms\Table\Abstracts\TableAbstract;
use Guestcms\Table\Actions\DeleteAction;
use Guestcms\Table\BulkActions\DeleteBulkAction;
use Guestcms\Table\BulkChanges\CreatedAtBulkChange;
use Guestcms\Table\BulkChanges\EmailBulkChange;
use Guestcms\Table\BulkChanges\NameBulkChange;
use Guestcms\Table\BulkChanges\StatusBulkChange;
use Guestcms\Table\Columns\CreatedAtColumn;
use Guestcms\Table\Columns\EmailColumn;
use Guestcms\Table\Columns\IdColumn;
use Guestcms\Table\Columns\NameColumn;
use Guestcms\Table\Columns\StatusColumn;
use Illuminate\Database\Eloquent\Builder;

class NewsletterTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(Newsletter::class)
            ->addColumns([
                IdColumn::make(),
                EmailColumn::make()->linkable(),
                NameColumn::make(),
                CreatedAtColumn::make(),
                StatusColumn::make(),
            ])
            ->addActions([
                DeleteAction::make()->route('newsletter.destroy'),
            ])
            ->addBulkAction(DeleteBulkAction::make()->permission('newsletter.destroy'))
            ->addBulkChanges([
                NameBulkChange::make(),
                EmailBulkChange::make(),
                StatusBulkChange::make(),
                CreatedAtBulkChange::make(),
            ])
            ->queryUsing(function (Builder $query) {
                return $query
                    ->select([
                        'id',
                        'email',
                        'name',
                        'created_at',
                        'status',
                    ]);
            });
    }

    public function getDefaultButtons(): array
    {
        return [
            'export',
            'reload',
        ];
    }
}
