<?php

namespace Guestcms\Slug\Providers;

use Guestcms\Base\Contracts\BaseModel;
use Guestcms\Base\Facades\Assets;
use Guestcms\Base\Forms\FormAbstract;
use Guestcms\Base\Supports\ServiceProvider;
use Guestcms\Slug\Facades\SlugHelper;
use Guestcms\Slug\Forms\Fields\PermalinkField;
use Guestcms\Theme\Facades\Theme;
use Guestcms\Theme\FormFront;

class HookServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        add_filter(BASE_FILTER_BEFORE_RENDER_FORM, [$this, 'addSlugBox'], 1712);

        add_filter('core_slug_language', [$this, 'setSlugLanguageForGenerator'], 17);
    }

    public function addSlugBox(FormAbstract $form): FormAbstract
    {
        if ($form->isDisabledPermalinkField()) {
            return $form;
        }

        $model = $form->getModel();

        if (! $model instanceof BaseModel || ! SlugHelper::isSupportedModel($model::class)) {
            return $form;
        }

        if (array_key_exists('slug', $form->getFields())) {
            return $form;
        }

        if ($form instanceof FormFront) {
            $version = get_cms_version();

            Theme::asset()->container('footer')->usePath(false)->add('slug-js', 'vendor/core/packages/slug/js/front-slug.js', ['jquery'], version: $version);
            Theme::asset()->usePath(false)->add('slug-css', 'vendor/core/packages/slug/css/slug.css', version: $version);
        } else {
            Assets::addScriptsDirectly('vendor/core/packages/slug/js/slug.js')->addStylesDirectly('vendor/core/packages/slug/css/slug.css');
        }

        return $form
            ->addAfter(SlugHelper::getColumnNameToGenerateSlug($model), 'slug', PermalinkField::class, [
                'model' => $model,
                'colspan' => 'full',
            ]);
    }

    public function setSlugLanguageForGenerator(): bool|string
    {
        return SlugHelper::turnOffAutomaticUrlTranslationIntoLatin() ? false : 'en';
    }
}
