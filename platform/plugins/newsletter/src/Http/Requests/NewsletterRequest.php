<?php

namespace Guestcms\Newsletter\Http\Requests;

use Guestcms\Newsletter\Enums\NewsletterStatusEnum;
use Guestcms\Newsletter\Models\Newsletter;
use Guestcms\Support\Http\Requests\Request;
use Illuminate\Database\Query\Builder;
use Illuminate\Validation\Rule;

class NewsletterRequest extends Request
{
    protected $errorBag = 'newsletter';

    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'email',
                Rule::unique((new Newsletter())->getTable())->where(function (Builder $query): void {
                    $query->where('status', NewsletterStatusEnum::SUBSCRIBED);
                }),
            ],
            'status' => Rule::in(NewsletterStatusEnum::values()),
        ];
    }
}
