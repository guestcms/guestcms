<?php

namespace Guestcms\Blog\Http\Controllers;

use Guestcms\Blog\Exporters\PostExporter;
use Guestcms\DataSynchronize\Exporter\Exporter;
use Guestcms\DataSynchronize\Http\Controllers\ExportController;

class ExportPostController extends ExportController
{
    protected function getExporter(): Exporter
    {
        return PostExporter::make();
    }
}
