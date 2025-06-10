<?php

namespace Guestcms\Setting\Models;

use Guestcms\Base\Models\BaseModel;

class Setting extends BaseModel
{
    protected $table = 'settings';

    protected $fillable = [
        'key',
        'value',
    ];
}
