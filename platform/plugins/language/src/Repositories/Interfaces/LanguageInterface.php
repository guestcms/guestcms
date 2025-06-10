<?php

namespace Guestcms\Language\Repositories\Interfaces;

use Guestcms\Base\Models\BaseModel;
use Guestcms\Language\Models\Language;
use Guestcms\Support\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface LanguageInterface extends RepositoryInterface
{
    public function getActiveLanguage(array $select = ['*']): Collection;

    public function getDefaultLanguage(array $select = ['*']): BaseModel|Model|Language|null;
}
