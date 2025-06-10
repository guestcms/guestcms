<?php

namespace Guestcms\Translation\Http\Controllers;

use Guestcms\Base\Http\Controllers\BaseController;
use Guestcms\Translation\Manager;

class ReImportThemeTranslationController extends BaseController
{
    public function __invoke(Manager $manager)
    {
        $manager->updateThemeTranslations();

        return $this->httpResponse()->setMessage(trans('plugins/translation::translation.import_success_message'));
    }
}
