<?php

namespace Guestcms\Base\Contracts;

use Illuminate\Support\Collection;

interface GlobalSearchableProvider
{
    /**
     * @return Collection<GlobalSearchableResult>
     */
    public function search(string $keyword): Collection;
}
