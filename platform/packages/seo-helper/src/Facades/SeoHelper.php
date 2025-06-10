<?php

namespace Guestcms\SeoHelper\Facades;

use Guestcms\SeoHelper\SeoHelper as BaseSeoHelper;
use Illuminate\Support\Facades\Facade;

/**
 * @method static static setSeoMeta(\Guestcms\SeoHelper\Contracts\SeoMetaContract $seoMeta)
 * @method static static setSeoOpenGraph(\Guestcms\SeoHelper\Contracts\SeoOpenGraphContract $seoOpenGraph)
 * @method static static setSeoTwitter(\Guestcms\SeoHelper\Contracts\SeoTwitterContract $seoTwitter)
 * @method static \Guestcms\SeoHelper\Contracts\SeoOpenGraphContract openGraph()
 * @method static static setTitle(string|null $title, string|null $siteName = null, string|null $separator = null)
 * @method static \Guestcms\SeoHelper\Contracts\SeoHelperContract setImage(string|null $image)
 * @method static \Guestcms\SeoHelper\Contracts\SeoMetaContract meta()
 * @method static \Guestcms\SeoHelper\Contracts\SeoTwitterContract twitter()
 * @method static string|null getTitle()
 * @method static string|null getTitleOnly()
 * @method static string|null getDescription()
 * @method static static setDescription($description)
 * @method static mixed render()
 * @method static bool saveMetaData(string $screen, \Illuminate\Http\Request $request, \Illuminate\Database\Eloquent\Model $object)
 * @method static bool deleteMetaData(string $screen, \Illuminate\Database\Eloquent\Model $object)
 * @method static array supportedModules()
 * @method static static registerModule(array|string $model)
 * @method static static removeModule(array|string $model)
 *
 * @see \Guestcms\SeoHelper\SeoHelper
 */
class SeoHelper extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return BaseSeoHelper::class;
    }
}
