<?php

namespace Guestcms\Table\Contracts;

use Guestcms\Base\Contracts\BaseModel;
use Guestcms\Table\Abstracts\TableAbstract;
use stdClass;

interface FormattedColumn
{
    public function formattedValue($value): ?string;

    public function renderCell(BaseModel|stdClass|array $item, TableAbstract $table): string;
}
