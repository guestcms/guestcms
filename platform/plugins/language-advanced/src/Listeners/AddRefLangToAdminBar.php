<?php

namespace Guestcms\LanguageAdvanced\Listeners;

use Guestcms\Language\Facades\Language;
use Guestcms\Theme\Facades\AdminBar;

class AddRefLangToAdminBar
{
    public function handle(): void
    {
        if (Language::getDefaultLocaleCode() === Language::getCurrentLocaleCode()) {
            return;
        }

        $groups = AdminBar::getLinksNoGroup();

        foreach ($groups as &$group) {
            $group['link'] .= sprintf('?%s=%s', Language::refLangKey(), Language::getCurrentLocaleCode());
        }

        AdminBar::setLinksNoGroup($groups);
    }
}
