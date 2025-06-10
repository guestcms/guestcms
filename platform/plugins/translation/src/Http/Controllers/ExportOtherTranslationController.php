<?php

namespace Guestcms\Translation\Http\Controllers;

use Guestcms\DataSynchronize\Exporter\Exporter;
use Guestcms\DataSynchronize\Http\Controllers\ExportController;
use Guestcms\Translation\Exporters\OtherTranslationExporter;

class ExportOtherTranslationController extends ExportController
{
    protected function getExporter(): Exporter
    {
        return OtherTranslationExporter::make();
    }
}
