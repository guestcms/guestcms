<?php

namespace Guestcms\Contact\Tables;

use Guestcms\Contact\Models\CustomField;
use Guestcms\Table\Abstracts\TableAbstract;
use Guestcms\Table\Actions\DeleteAction;
use Guestcms\Table\Actions\EditAction;
use Guestcms\Table\BulkActions\DeleteBulkAction;
use Guestcms\Table\BulkChanges\CreatedAtBulkChange;
use Guestcms\Table\BulkChanges\NameBulkChange;
use Guestcms\Table\Columns\CreatedAtColumn;
use Guestcms\Table\Columns\EnumColumn;
use Guestcms\Table\Columns\IdColumn;
use Guestcms\Table\Columns\NameColumn;
use Guestcms\Table\HeaderActions\CreateHeaderAction;
use Illuminate\Database\Eloquent\Builder;

class CustomFieldTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(CustomField::class)
            ->addHeaderActions([
                CreateHeaderAction::make()->route('contacts.custom-fields.create')->permission('contacts.edit'),
            ])
            ->addBulkChanges([
                NameBulkChange::make()->validate('required|max:120'),
                CreatedAtBulkChange::make(),
            ])
            ->addBulkAction(DeleteBulkAction::make()->permission('contacts.edit'))
            ->addActions([
                EditAction::make()->route('contacts.custom-fields.edit')->permission('contacts.edit'),
                DeleteAction::make()->route('contacts.custom-fields.destroy')->permission('contacts.edit'),
            ])
            ->addColumns([
                IdColumn::make(),
                NameColumn::make()->route('contacts.custom-fields.edit')->permission('contacts.edit'),
                EnumColumn::make('type')
                    ->title(trans('plugins/contact::contact.custom_field.type'))
                    ->alignLeft(),
                CreatedAtColumn::make(),
            ])
            ->queryUsing(fn (Builder $query) => $query->select([
                'id',
                'name',
                'type',
                'created_at',
            ]));
    }
}
