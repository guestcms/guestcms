<?php

namespace Guestcms\Hotel\Repositories\Interfaces;

use Guestcms\Support\Repositories\Interfaces\RepositoryInterface;

interface CurrencyInterface extends RepositoryInterface
{
    public function getAllCurrencies();
}
