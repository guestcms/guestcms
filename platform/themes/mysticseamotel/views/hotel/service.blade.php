<section
    class="breadcrumb-area"
    style="background-image: url({{ theme_option('rooms_banner') ? RvMedia::getImageUrl(theme_option('rooms_banner')) : Theme::asset()->url('img/bg/banner.jpg') }});"
>
    <div class="container">
        <div class="breadcrumb-text">
            <h2 class="page-title">{{ __('Service') }}</h2>

            {!! Theme::partial('breadcrumb') !!}
        </div>
    </div>
</section>

<section class="places-wrapper pt-120 pb-120">
    <div class="container">
        <div class="places-details">
            @if ($service->image)
                <div class="main-thumb mb-50">
                    <img
                        src="{{ RvMedia::getImageUrl($service->image) }}"
                        alt="images"
                    >
                </div>
            @endif
            <div class="title-wrap mb-50 d-flex align-items-center justify-content-between">
                <div class="title">
                    <h2>{{ $service->name }}</h2>
                    <span class="place-cat">
                        {{ __('Price') }}: @if ((float) $service->price > 0)
                            <em>{{ format_price($service->price) }}</em>
                        @else
                            <em>{{ __('Free') }}</em>
                        @endif
                    </span>
                </div>
            </div>
            <div class="ck-content">
                {!! BaseHelper::clean($service->content) !!}
            </div>
        </div>
    </div>
</section>
