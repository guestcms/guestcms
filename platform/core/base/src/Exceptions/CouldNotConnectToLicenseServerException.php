<?php

namespace Guestcms\Base\Exceptions;

use Guestcms\Base\Contracts\Exceptions\IgnoringReport;
use Illuminate\Http\Client\ConnectionException;

class CouldNotConnectToLicenseServerException extends ConnectionException implements IgnoringReport
{
}
