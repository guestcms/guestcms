<?php

namespace Guestcms\Gallery\Http\Requests;

use Guestcms\Base\Enums\BaseStatusEnum;
use Guestcms\Base\Rules\MediaImageRule;
use Guestcms\Base\Rules\OnOffRule;
use Guestcms\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class GalleryRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:250'],
            'description' => ['required', 'string', 'max:10000'],
            'order' => ['required', 'integer', 'min:0', 'max:127'],
            'status' => [Rule::in(BaseStatusEnum::values())],
            'is_featured' => [new OnOffRule()],
            'image' => ['nullable', 'string', new MediaImageRule()],
        ];
    }
}
