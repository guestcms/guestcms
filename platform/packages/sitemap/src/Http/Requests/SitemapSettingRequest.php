<?php

namespace Guestcms\Sitemap\Http\Requests;

use Guestcms\Base\Rules\OnOffRule;
use Guestcms\Support\Http\Requests\Request;

class SitemapSettingRequest extends Request
{
    public function rules(): array
    {
        return [
            'sitemap_enabled' => [new OnOffRule()],
            'sitemap_items_per_page' => ['nullable', 'integer', 'min:10', 'max:100000'],
        ];
    }
}
