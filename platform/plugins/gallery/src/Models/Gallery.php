<?php

namespace Guestcms\Gallery\Models;

use Guestcms\ACL\Models\User;
use Guestcms\Base\Casts\SafeContent;
use Guestcms\Base\Enums\BaseStatusEnum;
use Guestcms\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Gallery extends BaseModel
{
    protected $table = 'galleries';

    protected $fillable = [
        'name',
        'description',
        'is_featured',
        'order',
        'image',
        'status',
    ];

    protected $casts = [
        'status' => BaseStatusEnum::class,
        'name' => SafeContent::class,
        'description' => SafeContent::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault();
    }
}
