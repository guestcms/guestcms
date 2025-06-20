<?php

namespace Guestcms\Backup\Supports;

use Guestcms\Backup\Supports\MySql\MySqlDump;
use Guestcms\Backup\Supports\PgSql\Backup as PgSqlBackup;
use Guestcms\Backup\Supports\PgSql\Restore as PgSqlRestore;
use Guestcms\Base\Facades\BaseHelper;
use Guestcms\Base\Supports\Database;
use Guestcms\Base\Supports\Zipper;
use Carbon\Carbon;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;
use Symfony\Component\Process\Process;
use Throwable;

class Backup
{
    protected ?string $folder = null;

    public function __construct(protected Filesystem $files, protected Zipper $zipper)
    {
    }

    public function createBackupFolder(string $name, ?string $description = null): array
    {
        $backupFolder = $this->createFolder($this->getBackupPath());
        $now = Carbon::now()->format('Y-m-d-H-i-s');
        $this->folder = $this->createFolder($backupFolder . DIRECTORY_SEPARATOR . $now);

        $file = $this->getBackupPath('backup.json');
        $data = [];

        if ($this->files->exists($file)) {
            $data = BaseHelper::getFileData($file);
        }

        $data[$now] = [
            'name' => $name,
            'description' => $description,
            'date' => Carbon::now()->toDateTimeString(),
        ];

        BaseHelper::saveFileData($file, $data);

        return [
            'key' => $now,
            'data' => $data[$now],
        ];
    }

    public function createFolder(string $folder): string
    {
        $this->files->ensureDirectoryExists($folder);

        return $folder;
    }

    public function getBackupPath(?string $path = null): string
    {
        return storage_path('app/backup') . ($path ? '/' . $path : null);
    }

    public function getBackupDatabasePath(string $key): string
    {
        return $this->getBackupPath($key . '/database-' . $key . '.zip');
    }

    public function getBackupStoragePath(string $key): string
    {
        return $this->getBackupPath($key . '/storage-' . $key . '.zip');
    }

    public function isDatabaseBackupAvailable(string $key): bool
    {
        $file = $this->getBackupDatabasePath($key);

        return $this->files->exists($file) && $this->files->size($file) > 1024;
    }

    public function isStorageBackupAvailable(string $key): bool
    {
        $filePath = $this->getBackupStoragePath($key);

        if (! $this->files->exists($filePath)) {
            $backupPath = $this->getBackupPath($key);

            foreach (BaseHelper::scanFolder($backupPath) as $file) {
                if (Str::contains(basename($file), 'storage')) {
                    $filePath = $backupPath . DIRECTORY_SEPARATOR . $file;
                }
            }
        }

        return $this->files->exists($filePath) && $this->files->size($filePath) > 1024;
    }

    public function getBackupList(): array
    {
        $file = $this->getBackupPath('backup.json');
        if ($this->files->exists($file)) {
            return BaseHelper::getFileData($file);
        }

        return [];
    }

    public function backupDb(string $key = null): bool
    {
        if (! $key) {
            $key = Carbon::now()->format('Y-m-d-H-i-s');
        }

        $file = 'database-' . $key;
        $path = $this->folder . DIRECTORY_SEPARATOR . $file;

        $driver = DB::getConfig('driver');

        if (! $driver) {
            return false;
        }

        try {
            return match ($driver) {
                'mysql' => $this->backupDbMySql($path),
                'pgsql' => $this->backupDbPgSql($path),
                default => throw new RuntimeException(sprintf('Driver [%s] is not supported', $driver)),
            };
        } catch (Throwable $exception) {
            report($exception);

            return false;
        }
    }

    protected function backupDbMySql(string $path): bool
    {
        $config = DB::getConfig();
        $mysqlPath = rtrim(config('plugins.backup.general.mysql.execute_path'), '/');
        $command = $mysqlPath . 'mysqldump --user="%s" --password="%s" --host="%s" --port="%s" "%s" > "%s"';
        $sql = sprintf(
            $command,
            $config['username'],
            $config['password'],
            $config['host'],
            $config['port'],
            $config['database'],
            $filePath = $path . '.sql'
        );

        try {
            Process::fromShellCommandline($sql)->mustRun();
        } catch (Throwable $exception) {
            BaseHelper::logError($exception);

            try {
                if (function_exists('system')) {
                    system($sql);
                } else {
                    $this->processMySqlDumpPHP($path, $config);
                }
            } catch (Throwable $exception) {
                $this->processMySqlDumpPHP($path, $config);

                BaseHelper::logError($exception);
            }
        }

        if (! $this->files->exists($filePath) || $this->files->size($filePath) < 1024) {
            $this->processMySqlDumpPHP($path, $config);
        }

        $this->compressFileToZip($filePath, $fileZip = $path . '.zip');

        if ($this->files->exists($fileZip)) {
            $this->files->chmod($fileZip, 0755);
        }

        return true;
    }

    protected function processMySqlDumpPHP(string $path, array $config): bool
    {
        $dump = new MySqlDump('mysql:host=' . $config['host'] . ';dbname=' . $config['database'], $config['username'], $config['password']);

        $dump->start($path . '.sql');

        return true;
    }

    protected function backupDbPgSql(string $path): bool
    {
        $file = (new PgSqlBackup())->backup($path);

        $this->compressFileToZip($file, $fileZip = $path . '.zip');

        if ($this->files->exists($fileZip)) {
            $this->files->chmod($fileZip, 0755);
        }

        return true;
    }

    public function compressFileToZip(string $path, string $destination): void
    {
        $this->zipper->compress($path, $destination);

        $this->deleteFile($path);
    }

    protected function deleteFile(string $file): void
    {
        if ($this->files->exists($file)) {
            $this->files->delete($file);
        }
    }

    public function backupFolder(string $source, string $key = null): bool
    {
        if (! $key) {
            $key = Carbon::now()->format('Y-m-d-H-i-s');
        }

        $file = $this->folder . DIRECTORY_SEPARATOR . 'storage-' . $key . '.zip';

        BaseHelper::maximumExecutionTimeAndMemoryLimit();

        if (! $this->zipper->compress($source, $file)) {
            $this->deleteFolderBackup($this->folder);
        }

        if ($this->files->exists($file)) {
            $this->files->chmod($file, 0755);
        }

        return true;
    }

    public function deleteFolderBackup(string $path): void
    {
        $backupFolder = $this->getBackupPath();

        if ($this->files->isDirectory($backupFolder) && $this->files->isDirectory($path)) {
            foreach (BaseHelper::scanFolder($path) as $item) {
                $this->files->delete($path . DIRECTORY_SEPARATOR . $item);
            }
            $this->files->deleteDirectory($path);

            if (empty($this->files->directories($backupFolder))) {
                $this->files->deleteDirectory($backupFolder);
            }
        }

        $file = $this->getBackupPath('backup.json');
        $data = [];

        if ($this->files->exists($file)) {
            $data = BaseHelper::getFileData($file);
        }

        if (! empty($data)) {
            unset($data[Arr::last(explode('/', $path))]);
            BaseHelper::saveFileData($file, $data);
        }
    }

    public function restoreDatabase(string $file, string $path): bool
    {
        $driver = DB::getConfig('driver');

        if (! $driver) {
            return false;
        }

        $this->extractFileTo($file, $path);

        $file = $path . DIRECTORY_SEPARATOR . $this->files->name($file) . (
            $driver === 'mysql' ? '.sql' : '.dump'
        );

        if (! $this->files->exists($file) || $this->files->size($file) < 1024) {
            return false;
        }

        try {
            return match ($driver) {
                'mysql' => $this->restoreDbMySql($file),
                'pgsql' => $this->restoreDbPgSql($file),
                default => throw new RuntimeException(sprintf('Driver [%s] is not supported', $driver)),
            };
        } catch (Throwable $exception) {
            report($exception);

            return false;
        }
    }

    protected function restoreDbMySql(string $file): bool
    {
        Database::restoreFromPath($file);

        $this->deleteFile($file);

        return true;
    }

    protected function restoreDbPgSql(string $file): bool
    {
        (new PgSqlRestore())->restore($file);

        $this->deleteFile($file);

        return true;
    }

    public function extractFileTo(string $fileName, string $pathTo): bool
    {
        $this->zipper->extract($fileName, $pathTo);

        return true;
    }

    public function cleanDirectory(string $directory): bool
    {
        foreach ($this->files->glob(rtrim($directory, '/') . '/*') as $item) {
            if ($this->files->isDirectory($item)) {
                $this->files->deleteDirectory($item);
            } elseif (! in_array($this->files->basename($item), ['.htaccess', '.gitignore'])) {
                $this->files->delete($item);
            }
        }

        return true;
    }
}
