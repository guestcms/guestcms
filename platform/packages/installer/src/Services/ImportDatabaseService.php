<?php

namespace Guestcms\Installer\Services;

use Guestcms\Base\Services\ClearCacheService;
use Guestcms\Base\Supports\Database;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;

class ImportDatabaseService
{
    public function handle(string $path): void
    {
        try {
            Database::restoreFromPath($path);

            ClearCacheService::make()->purgeAll();
        } catch (QueryException $exception) {
            throw ValidationException::withMessages([
                'database' => [$exception->getMessage()],
            ]);
        }
    }
}
