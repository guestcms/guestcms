<?php

namespace Guestcms\Blog\Http\Requests;

use Guestcms\Base\Enums\BaseStatusEnum;
use Guestcms\Base\Rules\MediaImageRule;
use Guestcms\Base\Rules\OnOffRule;
use Guestcms\Blog\Models\Category;
use Guestcms\Blog\Supports\PostFormat;
use Guestcms\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class PostRequest extends Request
{
    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:250'],
            'description' => ['nullable', 'string', 'max:400'],
            'content' => ['nullable', 'string', 'max:300000'],
            'tag' => ['nullable', 'string', 'max:400'],
            'categories' => ['sometimes', 'array'],
            'categories.*' => ['sometimes', Rule::exists((new Category())->getTable(), 'id')],
            'status' => Rule::in(BaseStatusEnum::values()),
            'is_featured' => [new OnOffRule()],
            'image' => ['nullable', 'string', new MediaImageRule()],
        ];

        $postFormats = PostFormat::getPostFormats(true);

        if (count($postFormats) > 1) {
            $rules['format_type'] = Rule::in(array_keys($postFormats));
        }

        return $rules;
    }
}
