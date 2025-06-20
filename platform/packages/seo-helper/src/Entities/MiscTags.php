<?php

namespace Guestcms\SeoHelper\Entities;

use Guestcms\SeoHelper\Contracts\Entities\MetaCollectionContract;
use Guestcms\SeoHelper\Contracts\Entities\MiscTagsContract;
use Guestcms\SeoHelper\Contracts\Entities\WebmastersContract;

class MiscTags implements MiscTagsContract
{
    protected string $currentUrl = '';

    /**
     * Meta collection.
     *
     * @var MetaCollectionContract|WebmastersContract
     */
    protected $meta;

    /**
     * Make MiscTags instance.
     */
    public function __construct()
    {
        $this->meta = new MetaCollection();
        $this->addCanonical();
        $this->addMany(config('packages.seo-helper.general.misc.default', []));
    }

    /**
     * Get the current URL.
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->currentUrl;
    }

    /**
     * Set the current URL.
     *
     * @param string $url
     *
     * @return MiscTags
     */
    public function setUrl($url)
    {
        $this->currentUrl = $url;
        $this->addCanonical();

        return $this;
    }

    /**
     * Make MiscTags instance.
     *
     * @param array $defaults
     *
     * @return MiscTags
     */
    public static function make(array $defaults = [])
    {
        return new self();
    }

    /**
     * Add a meta tag.
     *
     * @param string $name
     * @param string $content
     *
     * @return MiscTags
     */
    public function add($name, $content)
    {
        $this->meta->add(compact('name', 'content'));

        return $this;
    }

    /**
     * Add many meta tags.
     *
     * @param array $meta
     *
     * @return MiscTags
     */
    public function addMany(array $meta)
    {
        $this->meta->addMany($meta);

        return $this;
    }

    /**
     * Remove a meta from the meta collection by key.
     *
     * @param array|string $names
     *
     * @return MiscTags
     */
    public function remove($names)
    {
        $this->meta->remove($names);

        return $this;
    }

    /**
     * Reset the meta collection.
     *
     * @return MiscTags
     */
    public function reset()
    {
        $this->meta->reset();

        return $this;
    }

    /**
     * Render the tag.
     *
     * @return string
     */
    public function render()
    {
        return $this->meta->render();
    }

    /**
     * Render the tag.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }

    protected function hasUrl(): bool
    {
        return ! empty($this->getUrl());
    }

    protected function addCanonical(): static
    {
        if ($this->hasUrl()) {
            $this->add('canonical', apply_filters('core_seo_canonical', $this->currentUrl));
        }

        return $this;
    }
}
