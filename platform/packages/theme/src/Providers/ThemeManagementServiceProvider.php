<?php

namespace Guestcms\Theme\Providers;

use Guestcms\Base\Facades\BaseHelper;
use Guestcms\Base\Facades\Html;
use Guestcms\Base\Supports\Helper;
use Guestcms\Base\Supports\ServiceProvider;
use Guestcms\Theme\Facades\Theme;
use Guestcms\Theme\Supports\ThemeSupport;
use Guestcms\Widget\AbstractWidget;
use Composer\Autoload\ClassLoader;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class ThemeManagementServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        if (Theme::hasInheritTheme()) {
            $this->loadJsonTranslationsFromTheme(
                Theme::getInheritTheme()
            );
        }

        $this->loadJsonTranslationsFromTheme(
            Theme::getThemeName()
        );
    }

    public function boot(): void
    {
        if (Theme::hasInheritTheme()) {
            $this->registerAutoloadPathFromTheme(
                Theme::getInheritTheme()
            );
        }

        $this->registerAutoloadPathFromTheme(
            Theme::getThemeName()
        );

        $this->app->booted(fn () => $this->bootWidgets());
    }

    protected function loadJsonTranslationsFromTheme(string $theme): void
    {
        $this->loadJsonTranslationsFrom(theme_path($theme . '/lang'));
        $this->loadJsonTranslationsFrom(lang_path('vendor/themes/' . $theme));
    }

    protected function registerAutoloadPathFromTheme(string $theme): void
    {
        if (empty($theme)) {
            return;
        }

        $themePath = theme_path($theme);

        $configFilePath = $themePath . '/theme.json';

        if ($this->app['files']->exists($configFilePath)) {
            $content = BaseHelper::getFileData($configFilePath);

            if (! empty($content) && Arr::has($content, 'namespace')) {
                $loader = new ClassLoader();
                $loader->setPsr4($content['namespace'], theme_path($theme . '/src'));
                $loader->register();
            }
        }

        Helper::autoload($themePath . '/functions');
    }

    protected function bootWidgets(): void
    {
        if (! class_exists('Guestcms\Widget\Providers\WidgetServiceProvider')) {
            return;
        }

        if (Theme::hasInheritTheme()) {
            $this->registerWidgetsFromTheme(Theme::getInheritTheme());
        }

        $this->registerWidgetsFromTheme(Theme::getThemeName());

        add_filter('widget_rendered', function (?string $html, AbstractWidget $widget) {
            if (! setting('show_theme_guideline_link', false) || ! Auth::guard()->check() || ! Auth::guard()->user()->hasPermission('widgets.index')) {
                return $html;
            }

            if ($widget->getConfig('ignore_guideline')) {
                return $html;
            }

            $editLink = route('widgets.index') . '?widget=' . $widget->getId();
            $link = view('packages/theme::guideline-link', [
                'html' => $html,
                'editLink' => $editLink,
                'editLabel' => __('Edit this widget'),
            ])->render();

            return ThemeSupport::insertBlockAfterTopHtmlTags($link, $html);
        }, 9999, 2);

        add_filter(THEME_FRONT_HEADER, function ($html) {
            if (! setting('show_theme_guideline_link', false) || ! Auth::guard()->check() || ! Auth::guard()->user()->hasPermission('widgets.index')) {
                return $html;
            }

            return $html . Html::style('vendor/core/packages/theme/css/guideline.css');
        }, 16);
    }

    protected function registerWidgetsFromTheme(string $theme): void
    {
        $widgetPath = theme_path($theme . '/widgets');

        $widgets = BaseHelper::scanFolder($widgetPath);

        if (! empty($widgets) && is_array($widgets)) {
            foreach ($widgets as $widget) {
                $registration = $widgetPath . '/' . $widget . '/registration.php';
                if ($this->app['files']->exists($registration)) {
                    $this->app['files']->requireOnce($registration);
                }
            }
        }
    }
}
