<?php

namespace Guestcms\Blog\Http\Requests;

use Guestcms\Base\Enums\BaseStatusEnum;
use Guestcms\Base\Rules\OnOffRule;
use Guestcms\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class CategoryRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:400'],
            'status' => [Rule::in(BaseStatusEnum::values())],
            'is_default' => [new OnOffRule()],
            'is_featured' => [new OnOffRule()],
            'parent_id' => [
                'nullable',
                Rule::when($this->input('parent_id'), function () {
                    return Rule::exists('categories', 'id');
                }),
            ],
            'order' => ['nullable', 'integer', 'min:0', 'max:10000'],
        ];
    }
}
