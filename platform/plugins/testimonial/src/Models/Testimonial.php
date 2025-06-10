<?php

namespace Guestcms\Testimonial\Models;

use Guestcms\Base\Casts\SafeContent;
use Guestcms\Base\Enums\BaseStatusEnum;
use Guestcms\Base\Models\BaseModel;

class Testimonial extends BaseModel
{
    protected $table = 'testimonials';

    protected $fillable = [
        'name',
        'company',
        'content',
        'image',
        'status',
    ];

    protected $casts = [
        'status' => BaseStatusEnum::class,
        'content' => SafeContent::class,
        'company' => SafeContent::class,
        'name' => SafeContent::class,
    ];
}
