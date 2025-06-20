<?php

namespace Guestcms\Base\Facades;

use Guestcms\Base\Supports\MacroableModels as MacroableModelsSupport;
use Illuminate\Support\Facades\Facade;

/**
 * @method static array getAllMacros()
 * @method static void addMacro(string $model, string $name, \Closure $closure)
 * @method static mixed getMacro(string $name)
 * @method static bool removeMacro(string $model, string $name)
 * @method static bool modelHasMacro(string $model, string $name)
 * @method static array modelsThatImplement(string $name)
 * @method static array macrosForModel(string $model)
 *
 * @see \Guestcms\Base\Supports\MacroableModels
 */
class MacroableModels extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return MacroableModelsSupport::class;
    }
}
