<?php

namespace Guestcms\Base\Http\Controllers;

use Guestcms\Base\Http\Controllers\Concerns\HasBreadcrumb;
use Guestcms\Base\Http\Controllers\Concerns\HasHttpResponse;
use Guestcms\Base\Http\Controllers\Concerns\HasPageTitle;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;

class BaseController extends Controller
{
    use HasBreadcrumb;
    use HasHttpResponse;
    use HasPageTitle;
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;
}
