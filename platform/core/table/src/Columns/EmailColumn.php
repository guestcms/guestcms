<?php

namespace Guestcms\Table\Columns;

use Guestcms\Base\Facades\Html;
use Guestcms\Table\Columns\Concerns\HasLink;
use Guestcms\Table\Contracts\FormattedColumn as FormattedColumnContract;

class EmailColumn extends FormattedColumn implements FormattedColumnContract
{
    use HasLink;

    public static function make(array|string $data = [], string $name = ''): static
    {
        return parent::make($data ?: 'email', $name)
            ->title(trans('core/base::tables.email'))
            ->alignStart();
    }

    public function formattedValue($value): ?string
    {
        if (! $this->isLinkable() || ! $value) {
            return null;
        }

        return Html::mailto($value, $value, [], true, false);
    }
}
