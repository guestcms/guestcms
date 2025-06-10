<?php

namespace Guestcms\ACL\Tables;

use Guestcms\ACL\Models\Role;
use Guestcms\Base\Facades\BaseHelper;
use Guestcms\Table\Abstracts\TableAbstract;
use Guestcms\Table\Actions\DeleteAction;
use Guestcms\Table\Actions\EditAction;
use Guestcms\Table\BulkActions\DeleteBulkAction;
use Guestcms\Table\BulkChanges\NameBulkChange;
use Guestcms\Table\Columns\CreatedAtColumn;
use Guestcms\Table\Columns\FormattedColumn;
use Guestcms\Table\Columns\IdColumn;
use Guestcms\Table\Columns\LinkableColumn;
use Guestcms\Table\Columns\NameColumn;
use Guestcms\Table\HeaderActions\CreateHeaderAction;
use Illuminate\Database\Eloquent\Builder;

class RoleTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(Role::class)
            ->addColumns([
                IdColumn::make(),
                NameColumn::make()->route('roles.edit'),
                FormattedColumn::make('description')
                    ->title(trans('core/base::tables.description'))
                    ->alignStart()
                    ->withEmptyState(),
                CreatedAtColumn::make(),
                LinkableColumn::make('created_by')
                    ->urlUsing(fn (LinkableColumn $column) => $column->getItem()->author->url)
                    ->title(trans('core/acl::permissions.created_by'))
                    ->width(100)
                    ->getValueUsing(function (LinkableColumn $column) {
                        return BaseHelper::clean($column->getItem()->author->name);
                    })
                    ->externalLink()
                    ->withEmptyState(),
            ])
            ->addHeaderAction(CreateHeaderAction::make()->route('roles.create'))
            ->addActions([
                EditAction::make()->route('roles.edit'),
                DeleteAction::make()->route('roles.destroy'),
            ])
            ->addBulkAction(DeleteBulkAction::make()->permission('roles.destroy'))
            ->addBulkChange(NameBulkChange::make())
            ->queryUsing(function (Builder $query): void {
                $query
                    ->with('author')
                    ->select([
                        'id',
                        'name',
                        'description',
                        'created_at',
                        'created_by',
                    ]);
            });
    }
}
