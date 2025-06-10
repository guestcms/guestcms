<?php

namespace Guestcms\Table\Columns;

use Guestcms\Base\Facades\Html;
use Guestcms\Table\Columns\Concerns\HasLink;
use Guestcms\Table\Contracts\FormattedColumn as FormattedColumnContract;

class PhoneColumn extends FormattedColumn implements FormattedColumnContract
{
    use HasLink;

    public static function make(array|string $data = [], string $name = ''): static
    {
        return parent::make($data ?: 'phone', $name)
            ->title(trans('core/base::tables.phone'))
            ->alignStart();
    }

    public function formattedValue($value): ?string
    {
        if (! $this->isLinkable() || ! $value) {
            return $value;
        }

        return Html::link('tel:' . $value, $value);
    }
}
