<?php

namespace Guestcms\Setting\Facades;

use Guestcms\Setting\Supports\SettingStore;
use Illuminate\Support\Facades\Facade;

/**
 * @method static mixed|null get(array|string $key, mixed|null $default = null)
 * @method static bool has(string $key)
 * @method static \Guestcms\Setting\Supports\SettingStore set(array|string $key, mixed|null $value = null, bool $force = false)
 * @method static \Guestcms\Setting\Supports\SettingStore forceSet(array|string $key, mixed|null $value = null)
 * @method static \Guestcms\Setting\Supports\SettingStore forget(string $key, bool $force = false)
 * @method static \Guestcms\Setting\Supports\SettingStore forgetAll()
 * @method static array all()
 * @method static bool save()
 * @method static void load(bool $force = false)
 * @method static mixed delete(array|string $keys = [], array $except = [], bool $force = false)
 * @method static mixed forceDelete(array|string $keys = [], array $except = [])
 * @method static \Illuminate\Database\Eloquent\Builder newQuery()
 *
 * @see \Guestcms\Setting\Supports\SettingStore
 */
class Setting extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return SettingStore::class;
    }
}
