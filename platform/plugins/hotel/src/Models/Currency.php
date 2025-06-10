<?php

namespace Guestcms\Hotel\Models;

use Guestcms\Base\Casts\SafeContent;
use Guestcms\Base\Models\BaseModel;

class Currency extends BaseModel
{
    protected $table = 'ht_currencies';

    protected $fillable = [
        'title',
        'symbol',
        'is_prefix_symbol',
        'order',
        'decimals',
        'is_default',
        'exchange_rate',
    ];

    protected $casts = [
        'title' => SafeContent::class,
    ];
}
