<?php

namespace Guestcms\SeoHelper;

use Guestcms\Base\Facades\BaseHelper;
use Guestcms\Media\Facades\RvMedia;
use Guestcms\SeoHelper\Contracts\Entities\OpenGraphContract;
use Guestcms\SeoHelper\Contracts\SeoOpenGraphContract;
use Guestcms\Theme\Facades\Theme;

class SeoOpenGraph implements SeoOpenGraphContract
{
    /**
     * The Open Graph instance.
     *
     * @var OpenGraphContract
     */
    protected $openGraph;

    /**
     * Make SeoOpenGraph instance.
     */
    public function __construct()
    {
        $this->setOpenGraph(
            new Entities\OpenGraph\Graph()
        );
    }

    /**
     * Set the Open Graph instance.
     *
     * @param OpenGraphContract $openGraph
     *
     * @return SeoOpenGraph
     */
    public function setOpenGraph(OpenGraphContract $openGraph)
    {
        $this->openGraph = $openGraph;

        return $this;
    }

    /**
     * Set the open graph prefix.
     *
     * @param string $prefix
     *
     * @return SeoOpenGraph
     */
    public function setPrefix($prefix)
    {
        $this->openGraph->setPrefix($prefix);

        return $this;
    }

    /**
     * Set type property.
     *
     * @param string $type
     *
     * @return SeoOpenGraph
     */
    public function setType($type)
    {
        $this->openGraph->setType($type);

        return $this;
    }

    /**
     * Set title property.
     *
     * @param string $title
     *
     * @return SeoOpenGraph
     */
    public function setTitle($title)
    {
        $this->openGraph->setTitle($title);

        return $this;
    }

    /**
     * Set description property.
     *
     * @param string $description
     *
     * @return SeoOpenGraph
     */
    public function setDescription($description)
    {
        $description = BaseHelper::cleanShortcodes($description);

        $this->openGraph->setDescription($description);

        return $this;
    }

    /**
     * Set url property.
     *
     * @param string $url
     *
     * @return SeoOpenGraph
     */
    public function setUrl($url)
    {
        $this->openGraph->setUrl($url);

        return $this;
    }

    /**
     * Set site name property.
     *
     * @param string $siteName
     *
     * @return SeoOpenGraph
     */
    public function setSiteName($siteName)
    {
        $this->openGraph->setSiteName($siteName);

        return $this;
    }

    /**
     * Add many open graph properties.
     *
     * @param array $properties
     *
     * @return SeoOpenGraph
     */
    public function addProperties(array $properties)
    {
        $this->openGraph->addProperties($properties);

        return $this;
    }

    /**
     * Add an open graph property.
     *
     * @param string $property
     * @param string $content
     *
     * @return SeoOpenGraph
     */
    public function addProperty($property, $content)
    {
        $this->openGraph->addProperty($property, $content);

        return $this;
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

    /**
     * Render the tag.
     *
     * @return string
     */
    public function render()
    {
        if (! $this->hasImage()) {
            $ogImage = theme_option('seo_og_image') ?: Theme::getLogo();

            $this->setImage(RvMedia::url($ogImage));
        }

        return $this->openGraph->render();
    }

    /**
     * @return bool
     */
    public function hasImage()
    {
        return $this->openGraph->hasImage();
    }

    public function getProperty(string $property): ?string
    {
        return $this->openGraph->getProperty($property);
    }

    /**
     * Set image property.
     *
     * @param string $image
     *
     * @return SeoOpenGraph
     */
    public function setImage($image)
    {
        $this->openGraph->setImage($image);

        return $this;
    }
}
