<?php

namespace Guestcms\Payment\Tables;

use Guestcms\Payment\Models\PaymentLog;
use Guestcms\Table\Abstracts\TableAbstract;
use Guestcms\Table\Actions\Action;
use Guestcms\Table\Actions\DeleteAction;
use Guestcms\Table\BulkActions\DeleteBulkAction;
use Guestcms\Table\Columns\Column;
use Guestcms\Table\Columns\DateTimeColumn;
use Guestcms\Table\Columns\EnumColumn;
use Guestcms\Table\Columns\IdColumn;
use Illuminate\Database\Eloquent\Builder;

class PaymentLogTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(PaymentLog::class)
            ->addActions([
                Action::make('view')
                    ->icon('ti ti-eye')
                    ->color('info')
                    ->route('payments.logs.show')
                    ->label(trans('core/base::tables.view')),
                DeleteAction::make()->route('payments.logs.destroy'),
            ])
            ->addColumns([
                IdColumn::make(),
                EnumColumn::make('payment_method'),
                Column::make('ip_address'),
                DateTimeColumn::make('created_at'),
            ])
            ->addBulkActions([
                DeleteBulkAction::make()->permission('payments.logs.destroy'),
            ])
            ->queryUsing(
                fn (Builder $query) => $query->select(['id', 'payment_method', 'request', 'ip_address', 'created_at'])
            );
    }
}
