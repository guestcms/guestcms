<?php

namespace Guestcms\Hotel\Services;

use Guestcms\Base\Models\BaseModel;
use Guestcms\Hotel\Enums\CouponTypeEnum;
use Guestcms\Hotel\Models\Coupon;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CouponService
{
    public function getCouponByCode(string $code): BaseModel|Model|null
    {
        return Coupon::query()
            ->where('code', $code)
            ->where(function (Builder $query): void {
                $query->whereNull('expires_date')
                    ->orWhere('expires_date', '>=', Carbon::now());
            })
            ->where(function (Builder $query): void {
                $query->whereNull('quantity')
                    ->orWhereColumn('quantity', '>', 'total_used');
            })
            ->first();
    }

    public function getDiscountAmount(string $type, float $value, float $amountTotal = 0): float
    {
        return match ($type) {
            CouponTypeEnum::PERCENTAGE => $value / 100 * $amountTotal,
            CouponTypeEnum::FIXED => $value,
            default => 0,
        };
    }
}
