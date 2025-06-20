<?php

namespace Guestcms\SeoHelper\Entities\OpenGraph;

use Guestcms\SeoHelper\Bases\MetaCollection as BaseMetaCollection;

class MetaCollection extends BaseMetaCollection
{
    protected $prefix = 'og:';

    protected $nameProperty = 'property';
}
