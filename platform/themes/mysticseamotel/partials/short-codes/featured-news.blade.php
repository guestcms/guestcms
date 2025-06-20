<section class="latest-news pt-115 pb-115">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 col-md-8 col-sm-7">
                <div class="section-title">
                    <span class="title-tag">{!! BaseHelper::clean($title) !!}</span>
                    <h2>{!! BaseHelper::clean($description ?: $subtitle) !!}</h2>
                </div>
            </div>
            <div class="col-lg-6 col-md-4 col-sm-5 d-none d-sm-block">
                <div class="latest-post-arrow arrow-style text-right">

                </div>
            </div>
        </div>
        <div class="row latest-post-slider mt-80">
            @foreach ($posts as $post)
                <div class="col-lg-4">
                    <div class="latest-post-box">
                        <div
                            class="post-img"
                            style="background-image: url({{ RvMedia::getImageUrl($post->image, '380x280', false, RvMedia::getDefaultImage()) }});"
                        ></div>
                        <div class="post-desc">
                            <ul class="post-meta">
                                <li>
                                    <a href="{{ $post->url }}"><i
                                            class="fal fa-calendar-alt"></i>{{ $post->created_at->translatedFormat('M d, Y') }}</a>
                                </li>
                                @if ($post->author && $post->author->id)
                                    <li>
                                        <a href="{{ $post->url }}"><i
                                                class="fal fa-user"></i>{{ $post->author->name }}</a>
                                    </li>
                                @endif
                            </ul>
                            <h3><a href="{{ $post->url }}">{{ $post->name }}</a></h3>
                            <p>{{ $post->description }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
