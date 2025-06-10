<?php

namespace Guestcms\Translation\Http\Controllers;

use Guestcms\Base\Facades\Assets;
use Guestcms\Setting\Http\Controllers\SettingController;
use Guestcms\Translation\Http\Controllers\Concerns\HasMapTranslationsTable;
use Guestcms\Translation\Http\Requests\TranslationRequest;
use Guestcms\Translation\Manager;
use Guestcms\Translation\Tables\TranslationTable;
use Illuminate\Http\Request;

class TranslationController extends SettingController
{
    use HasMapTranslationsTable;

    public function __construct(protected Manager $manager)
    {
    }

    public function index(Request $request, TranslationTable $translationTable)
    {
        $this->pageTitle(trans('plugins/translation::translation.admin-translations'));

        Assets::addScriptsDirectly('vendor/core/plugins/translation/js/translation.js')
            ->addStylesDirectly('vendor/core/plugins/translation/css/translation.css');

        [$locales, $locale, $defaultLanguage, $translationTable]
            = $this->mapTranslationsTable($translationTable, $request);

        if ($request->expectsJson()) {
            return $translationTable->renderTable();
        }

        return view(
            'plugins/translation::index',
            compact('locales', 'locale', 'defaultLanguage', 'translationTable')
        );
    }

    public function update(TranslationRequest $request)
    {
        $group = $request->input('group');

        $name = $request->input('name');
        $value = $request->input('value');

        [$locale, $key] = explode('|', $name, 2);

        $this->manager->updateTranslation($locale, $group, $key, $value);

        return $this->httpResponse();
    }
}
