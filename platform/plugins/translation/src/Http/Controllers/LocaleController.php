<?php

namespace Guestcms\Translation\Http\Controllers;

use Guestcms\Base\Exceptions\FileNotWritableException;
use Guestcms\Base\Facades\Assets;
use Guestcms\Base\Services\DeleteLocaleService;
use Guestcms\Base\Supports\Breadcrumb;
use Guestcms\Base\Supports\Language;
use Guestcms\Setting\Http\Controllers\SettingController;
use Guestcms\Translation\Http\Requests\LocaleRequest;
use Guestcms\Translation\Services\CreateLocaleService;
use Guestcms\Translation\Services\DownloadLocaleService;
use Illuminate\Support\Facades\File;
use Throwable;

class LocaleController extends SettingController
{
    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('plugins/translation::translation.locales'), route('translations.locales'));
    }

    public function index()
    {
        $this->pageTitle(trans('plugins/translation::translation.locales'));

        Assets::addScriptsDirectly('vendor/core/plugins/translation/js/locales.js');

        $existingLocales = Language::getAvailableLocales(true);
        $flags = Language::getListLanguageFlags();

        $locales = Language::getLocales();

        return view('plugins/translation::locales', compact('existingLocales', 'locales', 'flags'));
    }

    public function store(LocaleRequest $request, CreateLocaleService $createLocaleService)
    {
        $locale = $request->input('locale');

        if (! File::isDirectory(lang_path($locale))) {
            $createLocaleService->handle($locale);
        }

        return $this
            ->httpResponse()
            ->withCreatedSuccessMessage();
    }

    public function destroy(string $locale, DeleteLocaleService $deleteLocaleService)
    {
        try {
            $deleteLocaleService->handle($locale);

            return $this
                ->httpResponse()
                ->withDeletedSuccessMessage();
        } catch (FileNotWritableException $e) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage($e->getMessage());
        }
    }

    public function download(string $locale, DownloadLocaleService $downloadLocaleService)
    {
        try {
            $file = $downloadLocaleService->handle($locale);

            return response()->download($file)->deleteFileAfterSend();
        } catch (Throwable $exception) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }
}
