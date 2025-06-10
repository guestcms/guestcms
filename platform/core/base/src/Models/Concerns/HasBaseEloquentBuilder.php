<?php

namespace Guestcms\Base\Models\Concerns;

use Guestcms\Base\Models\BaseQueryBuilder;

trait HasBaseEloquentBuilder
{
    public function newEloquentBuilder($query): BaseQueryBuilder
    {
        return new BaseQueryBuilder($query);
    }
}
