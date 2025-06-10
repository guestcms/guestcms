<?php

namespace Guestcms\Newsletter\Http\Controllers;

use Guestcms\Base\Http\Actions\DeleteResourceAction;
use Guestcms\Base\Http\Controllers\BaseController;
use Guestcms\Newsletter\Models\Newsletter;
use Guestcms\Newsletter\Tables\NewsletterTable;

class NewsletterController extends BaseController
{
    public function index(NewsletterTable $dataTable)
    {
        $this->pageTitle(trans('plugins/newsletter::newsletter.name'));

        return $dataTable->renderTable();
    }

    public function destroy(Newsletter $newsletter)
    {
        return DeleteResourceAction::make($newsletter);
    }
}
