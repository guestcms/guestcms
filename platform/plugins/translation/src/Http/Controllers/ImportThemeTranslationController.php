<?php

namespace Guestcms\Translation\Http\Controllers;

use Guestcms\DataSynchronize\Http\Controllers\ImportController;
use Guestcms\DataSynchronize\Importer\Importer;
use Guestcms\Translation\Importers\ThemeTranslationImporter;

class ImportThemeTranslationController extends ImportController
{
    protected function getImporter(): Importer
    {
        return ThemeTranslationImporter::make();
    }
}
