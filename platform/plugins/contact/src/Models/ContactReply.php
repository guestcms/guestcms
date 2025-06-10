<?php

namespace Guestcms\Contact\Models;

use Guestcms\Base\Casts\SafeContent;
use Guestcms\Base\Models\BaseModel;

class ContactReply extends BaseModel
{
    protected $table = 'contact_replies';

    protected $fillable = [
        'message',
        'contact_id',
    ];

    protected $casts = [
        'message' => SafeContent::class,
    ];
}
