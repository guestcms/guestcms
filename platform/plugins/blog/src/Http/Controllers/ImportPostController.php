<?php

namespace Guestcms\Blog\Http\Controllers;

use Guestcms\Blog\Importers\PostImporter;
use Guestcms\DataSynchronize\Http\Controllers\ImportController;
use Guestcms\DataSynchronize\Importer\Importer;

class ImportPostController extends ImportController
{
    protected function getImporter(): Importer
    {
        return PostImporter::make();
    }
}
