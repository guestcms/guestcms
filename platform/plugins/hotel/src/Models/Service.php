<?php

namespace Guestcms\Hotel\Models;

use Guestcms\Base\Casts\SafeContent;
use Guestcms\Base\Enums\BaseStatusEnum;
use Guestcms\Base\Models\BaseModel;
use Guestcms\Hotel\Enums\ServicePriceTypeEnum;

class Service extends BaseModel
{
    protected $table = 'ht_services';

    protected $fillable = [
        'name',
        'description',
        'content',
        'price',
        'price_type',
        'image',
        'status',
    ];

    protected $casts = [
        'status' => BaseStatusEnum::class,
        'name' => SafeContent::class,
        'description' => SafeContent::class,
        'content' => SafeContent::class,
        'price_type' => ServicePriceTypeEnum::class,
    ];
}
