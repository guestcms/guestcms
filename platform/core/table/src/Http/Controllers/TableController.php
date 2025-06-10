<?php

namespace Guestcms\Table\Http\Controllers;

use Guestcms\Base\Http\Controllers\BaseController;
use Guestcms\Table\TableBuilder;

class TableController extends BaseController
{
    public function __construct(protected TableBuilder $tableBuilder)
    {
    }
}
