<?php

namespace Guestcms\Menu\Tables;

use Guestcms\Base\Facades\BaseHelper;
use Guestcms\Menu\Facades\Menu as MenuFacade;
use Guestcms\Menu\Models\Menu;
use Guestcms\Menu\Models\MenuLocation;
use Guestcms\Table\Abstracts\TableAbstract;
use Guestcms\Table\Actions\DeleteAction;
use Guestcms\Table\Actions\EditAction;
use Guestcms\Table\BulkActions\DeleteBulkAction;
use Guestcms\Table\BulkChanges\CreatedAtBulkChange;
use Guestcms\Table\BulkChanges\NameBulkChange;
use Guestcms\Table\BulkChanges\StatusBulkChange;
use Guestcms\Table\Columns\CreatedAtColumn;
use Guestcms\Table\Columns\FormattedColumn;
use Guestcms\Table\Columns\IdColumn;
use Guestcms\Table\Columns\NameColumn;
use Guestcms\Table\Columns\StatusColumn;
use Guestcms\Table\HeaderActions\CreateHeaderAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class MenuTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(Menu::class)
            ->addColumns([
                IdColumn::make(),
                NameColumn::make()->route('menus.edit'),
                FormattedColumn::make('locations_display')
                    ->label(trans('packages/menu::menu.locations'))
                    ->orderable(false)
                    ->searchable(false)
                    ->getValueUsing(function (FormattedColumn $column) {
                        $locations = $column
                            ->getItem()
                            ->locations
                            ->sortBy('name')
                            ->map(function (MenuLocation $location) {
                                $locationName = Arr::get(MenuFacade::getMenuLocations(), $location->location);

                                if (! $locationName) {
                                    return null;
                                }

                                return BaseHelper::renderBadge($locationName, 'info', ['class' => 'me-1']);
                            })
                            ->all();

                        return implode(', ', $locations);
                    })
                    ->withEmptyState(),
                FormattedColumn::make('items_count')
                    ->label(trans('packages/menu::menu.items'))
                    ->orderable(false)
                    ->searchable(false)
                    ->getValueUsing(function (FormattedColumn $column) {
                        return BaseHelper::renderIcon('ti ti-link') . ' '
                            . number_format($column->getItem()->menu_nodes_count);
                    }),
                CreatedAtColumn::make(),
                StatusColumn::make(),
            ])
            ->addHeaderAction(CreateHeaderAction::make()->route('menus.create'))
            ->addActions([
                EditAction::make()->route('menus.edit'),
                DeleteAction::make()->route('menus.destroy'),
            ])
            ->addBulkAction(DeleteBulkAction::make()->permission('menus.destroy'))
            ->addBulkChanges([
                NameBulkChange::make(),
                StatusBulkChange::make(),
                CreatedAtBulkChange::make(),
            ])
            ->queryUsing(function (Builder $query): void {
                $query
                    ->select([
                        'id',
                        'name',
                        'created_at',
                        'status',
                    ])
                    ->with('locations')
                    ->withCount('menuNodes');
            });
    }
}
