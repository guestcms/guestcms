<?php

namespace Guestcms\Theme;

use Guestcms\Base\Facades\Html;
use Guestcms\Theme\Facades\Theme as ThemeFacade;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class AssetContainer
{
    protected bool $usePath = false;

    protected string $path;

    protected array $assets = [];

    public function __construct(protected string $name)
    {
    }

    public function originUrl(?string $uri): string
    {
        return $this->configAssetUrl($uri);
    }

    protected function configAssetUrl(?string $path): string
    {
        return asset($path);
    }

    /**
     * Return asset path with current theme path.
     */
    public function url(?string $uri): string
    {
        // If path is full, so we just return.
        if (preg_match('#^http|//:#', $uri)) {
            return $uri;
        }

        $uri = ltrim($uri, '/');

        $path = $this->getCurrentPath() . $uri;
        $filePath = public_path($path);
        $pathExtension = File::extension($path);

        if (Str::contains($pathExtension, '?')) {
            $filePath = str_replace($pathExtension, Str::before($pathExtension, '?'), $filePath);
        }

        if (File::exists($filePath)) {
            return $this->configAssetUrl($path);
        }

        return $this->configAssetUrl($this->getInheritPath() . $uri);
    }

    /**
     * Get path from asset.
     */
    public function getCurrentPath(): string
    {
        $path = Asset::$path;

        return $this->isInheritTheme() ? $this->getInheritPath() : $path;
    }

    public function getInheritPath(): string
    {
        $path = Asset::$path;
        $inheritTheme = ThemeFacade::getInheritTheme();
        $theme = ThemeFacade::getThemeName();

        if (! $inheritTheme || $inheritTheme === $theme) {
            return $path;
        }

        return str_replace(
            '//',
            '/',
            str_replace($theme, $inheritTheme, $path)
        );
    }

    public function isInheritTheme(): bool
    {
        return Asset::$isInheritTheme;
    }

    /**
     * Add an asset to the container.
     *
     * The extension of the asset source will be used to determine the type of
     * asset being registered (CSS or JavaScript). When using a non-standard
     * extension, the style/script methods may be used to register assets.
     *
     * <code>
     *      // Add an asset to the container
     *      Asset::container()->add('jquery', 'js/jquery.js');
     *
     *      // Add an asset that has dependencies on other assets
     *      Asset::add('jquery', 'js/jquery.js', 'jquery-ui');
     *
     *      // Add an asset that should have attributes applied to its tags
     *      Asset::add('jquery', 'js/jquery.js', null, ['defer']);
     * </code>
     */
    public function add(
        string $name,
        string|array $source,
        array $dependencies = [],
        array $attributes = [],
        ?string $version = null
    ): self {
        if (is_array($source)) {
            foreach ($source as $path) {
                $name = $name . '-' . md5($path);

                $this->add($name, $path, $dependencies, $attributes);
            }

            return $this;
        }

        $type = File::extension($source) == 'css' ? 'style' : 'script';

        // Remove unnecessary slashes from internal path.
        if (! str_starts_with($source, '//')) {
            $source = ltrim($source, '/');
        }

        if ($version) {
            $source .= '?v=' . $version;
        }

        return $this->$type($name, $source, $dependencies, $attributes);
    }

    /**
     * Add an asset to the container using the path.
     *
     * The same `add` but usePath() before to add.
     *
     * <code>
     *      // Add an asset to the container
     *      Asset::container()->addUsingPath('jquery', 'js/jquery.js');
     *
     *      // Add an asset that has dependencies on other assets
     *      Asset::addUsingPath('jquery', 'js/jquery.js', 'jquery-ui');
     *
     *      // Add an asset that should have attributes applied to its tags
     *      Asset::addUsingPath('jquery', 'js/jquery.js', null, ['defer']);
     * </code>
     */
    public function addUsingPath(
        string $name,
        string|array $source,
        array $dependencies = [],
        array $attributes = [],
        ?string $version = null
    ): self {
        return $this
            ->usePath()
            ->add($name, $source, $dependencies, $attributes, $version);
    }

    public function remove(string|array $name): self
    {
        if (! is_array($name)) {
            $name = [$name];
        }

        foreach ($name as $item) {
            foreach ($this->assets as $typeKey => $type) {
                foreach ($type as $assetKey => $asset) {
                    if ($assetKey == $item) {
                        Arr::forget($this->assets, $typeKey . '.' . $assetKey);

                        break;
                    }
                }
            }
        }

        return $this;
    }

    public function get(string $name): array
    {
        foreach ($this->assets as $type) {
            foreach ($type as $assetKey => $asset) {
                if ($assetKey == $name) {
                    return $asset;
                }
            }
        }

        return [];
    }

    /**
     * Write a script to the container.
     */
    public function writeScript(string $name, string $source, array $dependencies = [], array $attributes = []): self
    {
        $source = '<script' . ($attributes ? Html::attributes($attributes) : '') . '>' . $source . '</script>';

        return $this->write($name, 'script', $source, $dependencies);
    }

    /**
     * Write a content to the container.
     */
    protected function write(string $name, string $type, string $source, array $dependencies = []): self
    {
        $types = [
            'script' => 'script',
            'style' => 'style',
            'js' => 'script',
            'css' => 'style',
        ];

        if (array_key_exists($type, $types)) {
            $type = $types[$type];

            $this->register($type, $name, $source, $dependencies, []);
        }

        return $this;
    }

    /**
     * Add an asset to the array of registered assets.
     */
    protected function register(
        string $type,
        string $name,
        string|array $source,
        array $dependencies,
        array $attributes
    ): void {
        $this->assets[$type][$name] = compact('source', 'dependencies', 'attributes');
    }

    /**
     * Write a style to the container.
     */
    public function writeStyle(string $name, string $source, array $dependencies = []): self
    {
        $source = '<style>' . $source . '</style>';

        return $this->write($name, 'style', $source, $dependencies);
    }

    /**
     * Write a content without tag wrapper.
     */
    public function writeContent(string $name, string $source, array $dependencies = []): self
    {
        return $this->write($name, 'script', $source, $dependencies);
    }

    /**
     * Add a CSS file to the registered assets.
     */
    public function style(string $name, string $source, array $dependencies = [], array $attributes = []): self
    {
        if (! array_key_exists('media', $attributes)) {
            $attributes['media'] = 'all';
        }

        // Prepend path to theme.
        if ($this->isUsePath()) {
            $source = $this->evaluatePath($this->getCurrentPath() . $source);

            // Reset using path.
            $this->usePath(false);
        }

        $this->register('style', $name, $source, $dependencies, $attributes);

        return $this;
    }

    /**
     * Add a CSS file to the registered assets using the path.
     */
    public function styleUsingPath(string $name, string $source, array $dependencies = [], array $attributes = []): self
    {
        return $this->usePath()->style($name, $source, $dependencies, $attributes);
    }

    /**
     * Check using theme path.
     */
    public function isUsePath(): bool
    {
        return $this->usePath;
    }

    /**
     * Evaluate path to current theme or force use theme.
     */
    protected function evaluatePath(string $source): string
    {
        $currentTheme = $this->isInheritTheme()
            ? ThemeFacade::getInheritTheme()
            : ThemeFacade::getThemeName();

        $isLocal = ! Str::startsWith($source, ['http://', 'https://']);

        // Switch path to another theme.
        if (! is_bool($this->usePath) && ThemeFacade::exists($this->usePath)) {
            $source = str_replace($currentTheme, $this->usePath, $source);
        }

        // If this is a child theme, and the file (local) does not exist in the child theme, use the parent theme.
        if (
            ThemeFacade::hasInheritTheme()
            && ! $this->isInheritTheme()
            && $isLocal
            && ! File::exists(public_path($source))
        ) {
            $source = str_replace($currentTheme, ThemeFacade::getInheritTheme(), $source);
        }

        $publicThemeName = $this->isInheritTheme() ? $currentTheme : ThemeFacade::getPublicThemeName();

        if ($publicThemeName != $currentTheme) {
            $source = str_replace($currentTheme, $publicThemeName, $source);
        }

        return $source;
    }

    /**
     * Force to use a theme path.
     */
    public function usePath(bool $use = true): self
    {
        $this->usePath = $use;

        return $this;
    }

    /**
     * Add a JavaScript file to the registered assets.
     */
    public function script(string $name, string $source, array $dependencies = [], array $attributes = []): self
    {
        // Prepend path to theme.
        if ($this->isUsePath()) {
            $source = $this->evaluatePath($this->getCurrentPath() . $source);

            // Reset using path.
            $this->usePath(false);
        }

        if ($name === 'jquery') {
            $attributes['data-pagespeed-no-defer'] = true;
        }

        $this->register('script', $name, $source, $dependencies, $attributes);

        return $this;
    }

    /**
     * Add a JavaScript file to the registered assets using the path.
     */
    public function scriptUsingPath(
        string $name,
        string $source,
        array $dependencies = [],
        array $attributes = []
    ): self {
        return $this->usePath()->script($name, $source, $dependencies, $attributes);
    }

    /**
     * Get the links to all the registered CSS assets.
     */
    public function styles(): string
    {
        return $this->group('style');
    }

    /**
     * Get all the registered assets for a given type / group.
     */
    protected function group(string $group): string
    {
        if (! isset($this->assets[$group]) || count($this->assets[$group]) == 0) {
            return '';
        }

        $assets = '';

        foreach (array_keys($this->arrange($this->assets[$group])) as $name) {
            $assets .= $this->asset($group, $name);
        }

        return $assets;
    }

    /**
     * Sort and retrieve assets based on their dependencies
     */
    protected function arrange(array $assets): array
    {
        [$original, $sorted] = [$assets, []];

        while (count($assets) > 0) {
            foreach ($assets as $asset => $value) {
                $this->evaluateAsset($asset, $value, $original, $sorted, $assets);
            }
        }

        return $sorted;
    }

    /**
     * Evaluate an asset and its dependencies.
     */
    protected function evaluateAsset(
        string $asset,
        string|null|array $value,
        array $original,
        array &$sorted,
        array &$assets
    ): void {
        // If the asset has no more dependencies, we can add it to the sorted list
        // and remove it from the array of assets. Otherwise, we will not verify
        // the asset's dependencies and determine if they've been sorted.
        if (count($assets[$asset]['dependencies']) == 0) {
            $sorted[$asset] = $value;

            unset($assets[$asset]);

            return;
        }

        foreach ($assets[$asset]['dependencies'] as $key => $dependency) {
            if (! $this->dependencyIsValid($asset, $dependency, $original, $assets)) {
                unset($assets[$asset]['dependencies'][$key]);

                continue;
            }

            // If the dependency has not yet been added to the sorted list, we can not
            // remove it from this asset's array of dependencies. We'll try again on
            // the next trip through the loop.
            if (! isset($sorted[$dependency])) {
                continue;
            }

            unset($assets[$asset]['dependencies'][$key]);
        }
    }

    /**
     * Verify that an asset's dependency is valid.
     * A dependency is considered valid if it exists, is not a circular reference, and is
     * not a reference to the owning asset itself. If the dependency doesn't exist, no
     * error or warning will be given. For the other cases, an exception is thrown.
     */
    protected function dependencyIsValid(string $asset, string $dependency, array $original, array $assets): bool
    {
        if (! isset($original[$dependency])) {
            return false;
        } elseif ($dependency === $asset) {
            throw new Exception('Asset [' . $asset . '] is dependent on itself.');
        } elseif (isset($assets[$dependency]) && in_array($asset, $assets[$dependency]['dependencies'])) {
            throw new Exception('Assets [' . $asset . '] and [' . $dependency . '] have a circular dependency.');
        }

        return true;
    }

    /**
     * Get the HTML link to a registered asset.
     */
    protected function asset(string $group, string $name): ?string
    {
        if (! isset($this->assets[$group][$name])) {
            return '';
        }

        $asset = $this->assets[$group][$name];

        // If the bundle source is not a complete URL, we will go ahead and prepend
        // the bundle's asset path to the source provided with the asset. This will
        // ensure that we attach the correct path to the asset.
        if (filter_var($asset['source'], FILTER_VALIDATE_URL) === false) {
            $asset['source'] = $this->path($asset['source']);
        }

        // If source is not a path to asset, render without wrap a HTML.
        if (str_contains($asset['source'], '<')) {
            return $asset['source'];
        }

        // This line fixing config path.
        $asset['source'] = $this->configAssetUrl($asset['source']);

        return $this->html($group, $asset['source'], $asset['attributes']);
    }

    /**
     * Returns the full-path for an asset.
     */
    public function path(string $source): string
    {
        return $source;
    }

    /**
     * Render asset as HTML.
     */
    public function html(string $group, string $source, array $attributes): ?string
    {
        switch ($group) {
            case 'script':
                $attributes['src'] = $source;

                return '<script' . $this->attributes($attributes) . '></script>' . PHP_EOL;
            case 'style':
                $defaults = ['media' => 'all', 'type' => 'text/css', 'rel' => 'stylesheet'];

                $attributes = $attributes + $defaults;

                $attributes['href'] = $source;

                return '<link' . $this->attributes($attributes) . '>' . PHP_EOL;
        }

        return null;
    }

    /**
     * Build an HTML attribute string from an array.
     */
    public function attributes(array $attributes): string
    {
        $html = [];

        // For numeric keys we will assume that the key and the value are the same
        // as this will convert HTML attributes such as "required" to a correct
        // form like required="required" instead of using incorrect numeric.
        foreach ($attributes as $key => $value) {
            $element = $this->attributeElement($key, $value);

            if (! empty($element)) {
                $html[] = $element;
            }
        }

        return count($html) > 0 ? ' ' . implode(' ', $html) : '';
    }

    /**
     * Build a single attribute element.
     */
    protected function attributeElement(string $key, ?string $value): ?string
    {
        if (is_numeric($key)) {
            return $value;
        }

        if (! empty($value)) {
            return $key . '="' . e($value) . '"';
        }

        return null;
    }

    public function getAssets(string $group): array
    {
        if (! isset($this->assets[$group])) {
            return [];
        }

        $assets = [];
        foreach (array_keys($this->arrange($this->assets[$group])) as $name) {
            $assets[] = $this->assetUrl($group, $name);
        }

        return $assets;
    }

    public function getAllAssets(): array
    {
        return $this->assets;
    }

    protected function assetUrl(string $group, string $name): string
    {
        if (! isset($this->assets[$group][$name])) {
            return '';
        }

        $asset = $this->assets[$group][$name];

        // If the bundle source is not a complete URL, we will go ahead and prepend
        // the bundle's asset path to the source provided with the asset. This will
        // ensure that we attach the correct path to the asset.
        if (filter_var($asset['source'], FILTER_VALIDATE_URL) === false) {
            $asset['source'] = $this->path($asset['source']);
        }

        // If source is not a path to asset, render without wrap an HTML.
        if (str_contains($asset['source'], '<')) {
            return $asset['source'];
        }

        return $this->configAssetUrl($asset['source']);
    }

    /**
     * Get the links to all the registered JavaScript assets.
     */
    public function scripts(): string
    {
        return $this->group('script');
    }
}
