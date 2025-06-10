<?php

namespace Guestcms\Hotel\Models;

use Guestcms\Base\Models\BaseModel;
use Guestcms\Hotel\Enums\CouponTypeEnum;

class Coupon extends BaseModel
{
    protected $table = 'ht_coupons';

    protected $fillable = [
        'type',
        'code',
        'value',
        'quantity',
        'total_used',
        'expires_date',
    ];

    protected $casts = [
        'type' => CouponTypeEnum::class,
        'value' => 'decimal:2',
        'quantity' => 'int',
        'total_used' => 'int',
        'expires_date' => 'datetime',
    ];
}
