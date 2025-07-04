<?php

namespace Guestcms\SocialLogin\Facades;

use Guestcms\SocialLogin\Supports\SocialService as SocialServiceSupport;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \Guestcms\SocialLogin\Supports\SocialService registerModule(array $model)
 * @method static array supportedModules()
 * @method static bool isSupportedModule(string $model)
 * @method static bool isSupportedModuleByKey(string $key)
 * @method static array|null getModule(string $key)
 * @method static bool isSupportedGuard(string $guard)
 * @method static array getEnvDisableData()
 * @method static string getDataDisable(string $key)
 * @method static string setting(string $key, bool $default = false)
 * @method static bool hasAnyProviderEnable()
 * @method static array getProviderKeys()
 * @method static array getProviders()
 * @method static array getDataProviderDefault()
 * @method static bool getProviderEnabled(string $provider)
 * @method static array getProviderKeysEnabled()
 * @method static array refreshToken(string $provider, string $refreshToken)
 *
 * @see \Guestcms\SocialLogin\Supports\SocialService
 */
class SocialService extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return SocialServiceSupport::class;
    }
}
