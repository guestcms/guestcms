<?php

namespace Guestcms\Base\Models;

use Guestcms\Base\Contracts\BaseModel as BaseModelContract;
use Guestcms\Base\Facades\MacroableModels;
use Guestcms\Base\Models\Concerns\HasBaseEloquentBuilder;
use Guestcms\Base\Models\Concerns\HasMetadata;
use Guestcms\Base\Models\Concerns\HasUuidsOrIntegerIds;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @method static \Guestcms\Base\Models\BaseQueryBuilder query()
 */
class BaseModel extends Model implements BaseModelContract
{
    use HasBaseEloquentBuilder;
    use HasMetadata;
    use HasUuidsOrIntegerIds;

    public function __get($key)
    {
        if (MacroableModels::modelHasMacro(static::class, $method = 'get' . Str::studly($key) . 'Attribute')) {
            return $this->{$method}();
        }

        return parent::__get($key);
    }
}
