<?php

namespace Guestcms\Base\Repositories\Eloquent;

use Guestcms\Base\Models\BaseModel;
use Guestcms\Base\Models\BaseQueryBuilder;
use Guestcms\Base\Models\MetaBox;
use Guestcms\Base\Repositories\Interfaces\MetaBoxInterface;
use Guestcms\Support\Repositories\Eloquent\RepositoriesAbstract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class MetaBoxRepository extends RepositoriesAbstract implements MetaBoxInterface
{
    public function __construct(protected BaseModel|BaseQueryBuilder|Builder|Model $model)
    {
        parent::__construct(new MetaBox());
    }
}
