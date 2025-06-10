<div class="d-flex align-items-center">
    <p class="mb-0">{{ __(':avg out of 5', ['avg' => number_format($avgStar, 1)]) }}</p>
    <div class="rating-wrap ms-1">
        <div class="rating">
            <div class="review-rate" style="width: {{ $avgStar * 20 }}%"></div>
        </div>
    </div>
</div>
