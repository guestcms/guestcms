<?php

namespace Guestcms\Hotel\Repositories\Eloquent;

use Guestcms\Hotel\Repositories\Interfaces\CurrencyInterface;
use Guestcms\Support\Repositories\Eloquent\RepositoriesAbstract;

class CurrencyRepository extends RepositoriesAbstract implements CurrencyInterface
{
    public function getAllCurrencies()
    {
        $data = $this->model
            ->orderBy('order', 'ASC')
            ->get();

        $this->resetModel();

        return $data;
    }
}
