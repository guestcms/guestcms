<?php

namespace Guestcms\Support\Services;

use Illuminate\Http\Request;

interface ProduceServiceInterface
{
    public function execute(Request $request);
}
