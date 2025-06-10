<?php

namespace Guestcms\Page\Http\Requests;

use Guestcms\Base\Enums\BaseStatusEnum;
use Guestcms\Base\Rules\MediaImageRule;
use Guestcms\Page\Supports\Template;
use Guestcms\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class PageRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:400'],
            'content' => ['nullable', 'string'],
            'template' => [Rule::in(array_keys(Template::getPageTemplates()))],
            'status' => [Rule::in(BaseStatusEnum::values())],
            'image' => ['nullable', 'string', new MediaImageRule()],
        ];
    }
}
