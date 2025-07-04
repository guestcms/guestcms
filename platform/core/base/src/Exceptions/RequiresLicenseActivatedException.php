<?php

namespace Guestcms\Base\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class RequiresLicenseActivatedException extends HttpException
{
    public function __construct($message = 'Please activate your license first.')
    {
        parent::__construct(403, $message);
    }
}
