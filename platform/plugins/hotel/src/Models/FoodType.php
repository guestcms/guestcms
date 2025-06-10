<?php

namespace Guestcms\Hotel\Models;

use Guestcms\Base\Casts\SafeContent;
use Guestcms\Base\Enums\BaseStatusEnum;
use Guestcms\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FoodType extends BaseModel
{
    protected $table = 'ht_food_types';

    protected $fillable = [
        'name',
        'icon',
        'status',
    ];

    protected $casts = [
        'status' => BaseStatusEnum::class,
        'name' => SafeContent::class,
        'icon' => SafeContent::class,
    ];

    public function foods(): HasMany
    {
        return $this->hasMany(Food::class, 'food_type_id');
    }
}
