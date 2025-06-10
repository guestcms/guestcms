<?php

namespace Guestcms\Language\Repositories\Eloquent;

use Guestcms\Base\Models\BaseModel;
use Guestcms\Language\Models\Language;
use Guestcms\Language\Repositories\Interfaces\LanguageInterface;
use Guestcms\Support\Repositories\Eloquent\RepositoriesAbstract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class LanguageRepository extends RepositoriesAbstract implements LanguageInterface
{
    public function getActiveLanguage(array $select = ['*']): Collection
    {
        $data = $this->model->orderBy('lang_order')->select($select)->get();
        $this->resetModel();

        return $data;
    }

    public function getDefaultLanguage(array $select = ['*']): BaseModel|Model|Language|null
    {
        $data = $this->model->where('lang_is_default', 1)->select($select)->first();
        $this->resetModel();

        return $data;
    }
}
