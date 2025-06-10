<?php

namespace Guestcms\Page\Tables;

use Guestcms\Page\Models\Page;
use Guestcms\Table\Abstracts\TableAbstract;
use Guestcms\Table\Actions\DeleteAction;
use Guestcms\Table\Actions\EditAction;
use Guestcms\Table\BulkActions\DeleteBulkAction;
use Guestcms\Table\BulkChanges\CreatedAtBulkChange;
use Guestcms\Table\BulkChanges\NameBulkChange;
use Guestcms\Table\BulkChanges\SelectBulkChange;
use Guestcms\Table\BulkChanges\StatusBulkChange;
use Guestcms\Table\Columns\CreatedAtColumn;
use Guestcms\Table\Columns\FormattedColumn;
use Guestcms\Table\Columns\IdColumn;
use Guestcms\Table\Columns\NameColumn;
use Guestcms\Table\Columns\StatusColumn;
use Guestcms\Table\HeaderActions\CreateHeaderAction;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class PageTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(Page::class)
            ->addHeaderAction(CreateHeaderAction::make()->route('pages.create'))
            ->addActions([
                EditAction::make()->route('pages.edit'),
                DeleteAction::make()->route('pages.destroy'),
            ])
            ->addColumns([
                IdColumn::make(),
                NameColumn::make()->route('pages.edit'),
                FormattedColumn::make('template')
                    ->title(trans('core/base::tables.template'))
                    ->alignStart()
                    ->getValueUsing(function (FormattedColumn $column) {
                        static $pageTemplates;

                        $pageTemplates ??= get_page_templates();

                        return Arr::get($pageTemplates, $column->getItem()->template ?: 'default');
                    }),
                CreatedAtColumn::make(),
                StatusColumn::make(),
            ])
            ->addBulkActions([
                DeleteBulkAction::make()->permission('pages.destroy'),
            ])
            ->addBulkChanges([
                NameBulkChange::make(),
                SelectBulkChange::make()
                    ->name('template')
                    ->title(trans('core/base::tables.template'))
                    ->choices(fn () => get_page_templates()),
                StatusBulkChange::make(),
                CreatedAtBulkChange::make(),
            ])
            ->queryUsing(function (Builder $query): void {
                $query->select([
                    'id',
                    'name',
                    'template',
                    'created_at',
                    'status',
                ]);
            });
    }
}
