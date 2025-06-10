<?php

namespace Guestcms\Hotel\Models;

use Guestcms\Base\Casts\SafeContent;
use Guestcms\Base\Enums\BaseStatusEnum;
use Guestcms\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Food extends BaseModel
{
    protected $table = 'ht_foods';

    protected $fillable = [
        'name',
        'description',
        'content',
        'price',
        'currency_id',
        'food_type_id',
        'image',
        'status',
    ];

    protected $casts = [
        'status' => BaseStatusEnum::class,
        'name' => SafeContent::class,
        'description' => SafeContent::class,
    ];

    public function type(): BelongsTo
    {
        return $this->belongsTo(FoodType::class, 'food_type_id')->withDefault();
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id')->withDefault();
    }
}
