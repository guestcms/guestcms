<?php

namespace Guestcms\Newsletter\Models;

use Guestcms\Base\Casts\SafeContent;
use Guestcms\Base\Models\BaseModel;
use Guestcms\Newsletter\Enums\NewsletterStatusEnum;

class Newsletter extends BaseModel
{
    protected $table = 'newsletters';

    protected $fillable = [
        'email',
        'name',
        'status',
    ];

    protected $casts = [
        'name' => SafeContent::class,
        'status' => NewsletterStatusEnum::class,
    ];
}
