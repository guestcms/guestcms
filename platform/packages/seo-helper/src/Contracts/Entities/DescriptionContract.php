<?php

namespace Guestcms\SeoHelper\Contracts\Entities;

use Guestcms\SeoHelper\Contracts\RenderableContract;

interface DescriptionContract extends RenderableContract
{
    /**
     * Get raw description content.
     *
     * @return string
     */
    public function getContent();

    /**
     * Get description content.
     *
     * @return string
     */
    public function get();

    /**
     * Set description content.
     *
     * @param string $content
     * @return $this
     */
    public function set($content);

    /**
     * Get description max length.
     *
     * @return int
     */
    public function getMax();

    /**
     * Set description max length.
     *
     * @param int $max
     * @return $this
     */
    public function setMax($max);

    public static function make();
}
