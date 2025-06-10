<?php

namespace Guestcms\Optimize\Facades;

use Guestcms\Optimize\Supports\Optimizer;
use Illuminate\Support\Facades\Facade;

/**
 * @method static bool isEnabled()
 * @method static \Guestcms\Optimize\Supports\Optimizer enable()
 * @method static \Guestcms\Optimize\Supports\Optimizer disable()
 *
 * @see \Guestcms\Optimize\Supports\Optimizer
 */
class OptimizerHelper extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return Optimizer::class;
    }
}
