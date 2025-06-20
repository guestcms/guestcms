<?php

namespace Guestcms\ACL\Repositories\Eloquent;

use Guestcms\ACL\Repositories\Interfaces\RoleInterface;
use Guestcms\Support\Repositories\Eloquent\RepositoriesAbstract;
use Illuminate\Support\Str;

class RoleRepository extends RepositoriesAbstract implements RoleInterface
{
    public function createSlug(string $name, int|string $id): string
    {
        $slug = Str::slug($name);
        $index = 1;
        $baseSlug = $slug;
        while ($this->model->where('slug', $slug)->where('id', '!=', $id)->exists()) {
            $slug = $baseSlug . '-' . $index++;
        }

        if (empty($slug)) {
            $slug = time();
        }

        $this->resetModel();

        return $slug;
    }
}
