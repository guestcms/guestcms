<?php

namespace Guestcms\Blog\Http\Requests\Settings;

use Guestcms\Base\Rules\OnOffRule;
use Guestcms\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class BlogSettingRequest extends Request
{
    public function rules(): array
    {
        return [
            'blog_post_schema_enabled' => new OnOffRule(),
            'blog_post_schema_type' => [
                'nullable',
                'string',
                Rule::in(['NewsArticle', 'News', 'Article', 'BlogPosting']),
            ],
        ];
    }
}
