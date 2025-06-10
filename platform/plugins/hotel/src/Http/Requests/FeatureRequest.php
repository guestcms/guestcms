<?php

namespace Guestcms\Hotel\Http\Requests;

use Guestcms\Base\Enums\BaseStatusEnum;
use Guestcms\Base\Rules\OnOffRule;
use Guestcms\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class FeatureRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'icon' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:1000'],
            'is_featured' => new OnOffRule(),
            'status' => Rule::in(BaseStatusEnum::values()),
        ];
    }
}
