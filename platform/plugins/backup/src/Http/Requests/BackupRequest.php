<?php

namespace Guestcms\Backup\Http\Requests;

use Guestcms\Support\Http\Requests\Request;

class BackupRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:250'],
            'backup_only_db' => ['nullable', 'boolean'],
        ];
    }
}
