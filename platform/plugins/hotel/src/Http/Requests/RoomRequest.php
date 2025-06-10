<?php

namespace Guestcms\Hotel\Http\Requests;

use Guestcms\Base\Enums\BaseStatusEnum;
use Guestcms\Base\Rules\OnOffRule;
use Guestcms\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class RoomRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:400'],
            'status' => Rule::in(BaseStatusEnum::values()),
            'is_featured' => new OnOffRule(),
            'content' => ['nullable', 'string', 'max:100000'],
            'order' => ['nullable', 'numeric'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'number_of_rooms' => ['nullable', 'integer', 'min:1'],
            'number_of_beds' => ['nullable', 'integer', 'min:0'],
            'max_adults' => ['nullable', 'numeric', 'min:0'],
            'max_children' => ['nullable', 'numeric', 'min:0'],
            'size' => ['nullable', 'numeric', 'min:0'],
            'images' => ['nullable', 'array'],
            'images.*' => ['nullable', 'string'],
            'room_category_id' => ['required', 'string', 'exists:ht_room_categories,id'],
            'tax_id' => ['required', 'string', 'exists:ht_taxes,id'],
        ];
    }
}
