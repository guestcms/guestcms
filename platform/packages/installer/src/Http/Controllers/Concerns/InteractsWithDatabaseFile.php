<?php

namespace Guestcms\Installer\Http\Controllers\Concerns;

use Guestcms\Installer\Services\ImportDatabaseService;
use Illuminate\Support\Facades\File;

trait InteractsWithDatabaseFile
{
    protected function handleImportDatabaseFile(ImportDatabaseService $importDatabaseService, string $fileName): void
    {
        $databaseToImport = base_path(sprintf('database-%s.sql', $fileName));

        if (! File::exists($databaseToImport)) {
            $databaseToImport = database_path(sprintf('sample/database-%s.sql', $fileName));
        }

        if (! File::exists($databaseToImport)) {
            $databaseToImport = database_path('sample/database.sql');
        }

        if (! File::exists($databaseToImport)) {
            $databaseToImport = base_path('database.sql');
        }

        $importDatabaseService->handle($databaseToImport);
    }
}
