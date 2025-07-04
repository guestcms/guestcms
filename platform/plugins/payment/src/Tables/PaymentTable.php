<?php

namespace Guestcms\Payment\Tables;

use Guestcms\Base\Facades\Html;
use Guestcms\Payment\Enums\PaymentStatusEnum;
use Guestcms\Payment\Models\Payment;
use Guestcms\Table\Abstracts\TableAbstract;
use Guestcms\Table\Actions\DeleteAction;
use Guestcms\Table\Actions\EditAction;
use Guestcms\Table\BulkActions\DeleteBulkAction;
use Guestcms\Table\BulkChanges\CreatedAtBulkChange;
use Guestcms\Table\BulkChanges\StatusBulkChange;
use Guestcms\Table\BulkChanges\TextBulkChange;
use Guestcms\Table\Columns\Column;
use Guestcms\Table\Columns\CreatedAtColumn;
use Guestcms\Table\Columns\IdColumn;
use Guestcms\Table\Columns\StatusColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class PaymentTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(Payment::class)
            ->addActions([
                EditAction::make()->route('payment.show'),
                DeleteAction::make()->route('payment.destroy'),
            ]);
    }

    public function ajax(): JsonResponse
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('charge_id', function (Payment $item) {
                return Html::link(route('payment.show', $item->getKey()), Str::limit($item->charge_id, 20));
            })
            ->editColumn('customer_id', function (Payment $item) {
                if ($item->customer_id && $item->customer_type && class_exists($item->customer_type)) {
                    return $item->customer->name;
                }

                if ($item->order && $item->order->address) {
                    return $item->order->address->name;
                }

                return apply_filters('payment_table_payer_name', '&mdash;', $item);
            })
            ->editColumn('payment_channel', function (Payment $item) {
                return $item->payment_channel->label() ?: '&mdash;';
            })
            ->editColumn('amount', function (Payment $item) {
                return $item->amount . ' ' . $item->currency;
            });

        return $this->toJson($data);
    }

    public function query(): Relation|Builder|QueryBuilder
    {
        $query = $this
            ->getModel()
            ->query()
            ->select([
                'id',
                'charge_id',
                'amount',
                'currency',
                'payment_channel',
                'created_at',
                'status',
                'order_id',
                'customer_id',
                'customer_type',
            ])
            ->with(['customer']);

        if (method_exists($query->getModel(), 'order')) {
            $query->with(['customer', 'order']);
        }

        return $this->applyScopes($query);
    }

    public function columns(): array
    {
        return [
            IdColumn::make(),
            Column::make('charge_id')
                ->title(trans('plugins/payment::payment.charge_id')),
            Column::make('customer_id')
                ->title(trans('plugins/payment::payment.payer_name'))
                ->alignStart(),
            Column::make('amount')
                ->title(trans('plugins/payment::payment.amount'))
                ->alignStart(),
            Column::make('payment_channel')
                ->title(trans('plugins/payment::payment.payment_channel'))
                ->alignStart(),
            StatusColumn::make(),
            CreatedAtColumn::make(),
        ];
    }

    public function bulkActions(): array
    {
        return [
            DeleteBulkAction::make()->permission('payment.destroy'),
        ];
    }

    public function getBulkChanges(): array
    {
        return [
            StatusBulkChange::make()->choices(PaymentStatusEnum::labels()),
            TextBulkChange::make()
                ->name('charge_id')
                ->title(trans('plugins/payment::payment.charge_id'))
                ->validate('required|max:120'),
            CreatedAtBulkChange::make(),
        ];
    }

    public function saveBulkChangeItem(Model|Payment $item, string $inputKey, ?string $inputValue): Model|bool
    {
        if ($inputKey === 'status') {
            $request = request();

            $request->merge(['status' => $inputValue]);

            do_action(ACTION_AFTER_UPDATE_PAYMENT, $request, $item);
        }

        return parent::saveBulkChangeItem($item, $inputKey, $inputValue);
    }
}
