<?php

namespace Guestcms\Base\Http\Controllers\Concerns;

use Guestcms\Base\Http\Responses\BaseHttpResponse;

trait HasHttpResponse
{
    public function httpResponse(): BaseHttpResponse
    {
        return BaseHttpResponse::make();
    }
}
