<?php

namespace Guestcms\Hotel\Models;

use Guestcms\Base\Casts\SafeContent;
use Guestcms\Base\Enums\BaseStatusEnum;
use Guestcms\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RoomCategory extends BaseModel
{
    protected $table = 'ht_room_categories';

    protected $fillable = [
        'name',
        'is_featured',
        'order',
        'status',
    ];

    protected $casts = [
        'status' => BaseStatusEnum::class,
        'name' => SafeContent::class,
    ];

    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }

    protected static function booted(): void
    {
        static::deleting(function (RoomCategory $category): void {
            $category->rooms()->update(['room_category_id' => null]);
        });
    }
}
