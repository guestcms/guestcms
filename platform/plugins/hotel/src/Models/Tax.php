<?php

namespace Guestcms\Hotel\Models;

use Guestcms\Base\Casts\SafeContent;
use Guestcms\Base\Enums\BaseStatusEnum;
use Guestcms\Base\Models\BaseModel;

class Tax extends BaseModel
{
    protected $table = 'ht_taxes';

    protected $fillable = [
        'title',
        'percentage',
        'priority',
        'status',
    ];

    protected $casts = [
        'status' => BaseStatusEnum::class,
        'title' => SafeContent::class,
    ];
}
