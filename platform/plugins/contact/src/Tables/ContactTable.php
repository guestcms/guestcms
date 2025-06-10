<?php

namespace Guestcms\Contact\Tables;

use Guestcms\Contact\Enums\ContactStatusEnum;
use Guestcms\Contact\Exports\ContactExport;
use Guestcms\Contact\Models\Contact;
use Guestcms\Table\Abstracts\TableAbstract;
use Guestcms\Table\Actions\DeleteAction;
use Guestcms\Table\Actions\EditAction;
use Guestcms\Table\BulkActions\DeleteBulkAction;
use Guestcms\Table\BulkChanges\CreatedAtBulkChange;
use Guestcms\Table\BulkChanges\EmailBulkChange;
use Guestcms\Table\BulkChanges\NameBulkChange;
use Guestcms\Table\BulkChanges\PhoneBulkChange;
use Guestcms\Table\BulkChanges\StatusBulkChange;
use Guestcms\Table\Columns\CreatedAtColumn;
use Guestcms\Table\Columns\EmailColumn;
use Guestcms\Table\Columns\IdColumn;
use Guestcms\Table\Columns\NameColumn;
use Guestcms\Table\Columns\PhoneColumn;
use Guestcms\Table\Columns\StatusColumn;
use Illuminate\Database\Eloquent\Builder;

class ContactTable extends TableAbstract
{
    protected string $exportClass = ContactExport::class;

    public function setup(): void
    {
        $this
            ->model(Contact::class)
            ->addActions([
                EditAction::make()->route('contacts.edit'),
                DeleteAction::make()->route('contacts.destroy'),
            ])
            ->addColumns([
                IdColumn::make(),
                NameColumn::make()->route('contacts.edit'),
                EmailColumn::make()->linkable()->withEmptyState(),
                PhoneColumn::make()->linkable()->withEmptyState(),
                CreatedAtColumn::make(),
                StatusColumn::make(),
            ])
            ->addBulkActions([
                DeleteBulkAction::make()->permission('contacts.destroy'),
            ])
            ->addBulkChanges([
                NameBulkChange::make(),
                EmailBulkChange::make(),
                StatusBulkChange::make()->choices(ContactStatusEnum::labels()),
                CreatedAtBulkChange::make(),
                PhoneBulkChange::make()->title(trans('plugins/contact::contact.sender_phone')),
            ])
            ->queryUsing(function (Builder $query) {
                return $query
                    ->select([
                        'id',
                        'name',
                        'phone',
                        'email',
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
