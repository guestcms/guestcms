<?php

namespace Guestcms\Analytics\Facades;

use Guestcms\Analytics\Abstracts\AnalyticsAbstract;
use Illuminate\Support\Facades\Facade;

/**
 * @method static string getCredentials()
 * @method static \Google\Analytics\Data\V1beta\Client\BetaAnalyticsDataClient getClient()
 * @method static \Guestcms\Analytics\AnalyticsResponse get()
 * @method static \Illuminate\Support\Collection fetchMostVisitedPages(\Guestcms\Analytics\Period $period, int $maxResults = 20)
 * @method static \Illuminate\Support\Collection fetchTopReferrers(\Guestcms\Analytics\Period $period, int $maxResults = 20)
 * @method static \Illuminate\Support\Collection fetchTopBrowsers(\Guestcms\Analytics\Period $period, int $maxResults = 10)
 * @method static \Illuminate\Support\Collection performQuery(\Guestcms\Analytics\Period $period, array|string $metrics, array|string $dimensions = [])
 * @method static string getPropertyId()
 * @method static void macro(string $name, object|callable $macro)
 * @method static void mixin(object $mixin, bool $replace = true)
 * @method static bool hasMacro(string $name)
 * @method static void flushMacros()
 * @method static \Guestcms\Analytics\Analytics dateRange(\Guestcms\Analytics\Period $period)
 * @method static \Guestcms\Analytics\Analytics dateRanges(\Guestcms\Analytics\Period ...$items)
 * @method static \Guestcms\Analytics\Analytics metric(string $name)
 * @method static \Guestcms\Analytics\Analytics metrics(array|string $items)
 * @method static \Guestcms\Analytics\Analytics dimension(string $name)
 * @method static \Guestcms\Analytics\Analytics dimensions(array|string $items)
 * @method static \Guestcms\Analytics\Analytics orderByMetric(string $name, string $order = 'ASC')
 * @method static \Guestcms\Analytics\Analytics orderByMetricDesc(string $name)
 * @method static \Guestcms\Analytics\Analytics orderByDimension(string $name, string $order = 'ASC')
 * @method static \Guestcms\Analytics\Analytics orderByDimensionDesc(string $name)
 * @method static \Guestcms\Analytics\Analytics metricAggregation(int $value)
 * @method static \Guestcms\Analytics\Analytics metricAggregations(int ...$items)
 * @method static \Guestcms\Analytics\Analytics whereDimension(string $name, int $matchType, $value, bool $caseSensitive = false)
 * @method static \Guestcms\Analytics\Analytics whereDimensionIn(string $name, array $values, bool $caseSensitive = false)
 * @method static \Guestcms\Analytics\Analytics whereMetric(string $name, int $operation, $value)
 * @method static \Guestcms\Analytics\Analytics whereMetricBetween(string $name, $from, $to)
 * @method static \Guestcms\Analytics\Analytics keepEmptyRows(bool $keepEmptyRows = false)
 * @method static \Guestcms\Analytics\Analytics limit(int|null $limit = null)
 * @method static \Guestcms\Analytics\Analytics offset(int|null $offset = null)
 *
 * @see \Guestcms\Analytics\Analytics
 */
class Analytics extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return AnalyticsAbstract::class;
    }
}
