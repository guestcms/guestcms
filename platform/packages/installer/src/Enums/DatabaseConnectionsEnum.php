<?php

namespace Guestcms\Installer\Enums;

use Guestcms\Base\Supports\Enum;

/**
 * @method static DatabaseConnectionsEnum MYSQL()
 * @method static DatabaseConnectionsEnum SQLITE()
 * @method static DatabaseConnectionsEnum PGSQL()
 */
class DatabaseConnectionsEnum extends Enum
{
    public const MYSQL = 'mysql';
    public const SQLITE = 'sqlite';
    public const PGSQL = 'pgsql';

    public static $langPath = 'packages/installer::installer.environment.wizard.form.db_connections';
}
