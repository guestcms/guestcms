<div class="mb-3">
    <label class="form-label">{{ __('Title') }}</label>
    <input
        class="form-control"
        name="title"
        type="text"
        value="{{ Arr::get($attributes, 'title') }}"
        placeholder="{{ __('Title') }}"
    >
</div>

<div class="mb-3">
    <label class="form-label">{{ __('Subtitle') }}</label>
    <textarea
        class="form-control"
        name="subtitle"
        placeholder="{{ __('Subtitle') }}"
        rows="3"
    >{{ Arr::get($attributes, 'subtitle') }}</textarea>
</div>

@for ($i = 1; $i <= 5; $i++)
    <div class="mb-3">
        <label class="form-label">{{ __('Block icon :number', ['number' => $i]) }}</label>
        {!! Form::themeIcon('block_icon_' . $i, Arr::get($attributes, 'block_icon_' . $i)) !!}
    </div>

    <div class="mb-3">
        <label class="form-label">{{ __('Block text :number', ['number' => $i]) }}</label>
        <input
            class="form-control"
            name="block_text_{{ $i }}"
            type="text"
            value="{{ Arr::get($attributes, 'block_text_' . $i) }}"
            placeholder="{{ __('Block text :number', ['number' => $i]) }}"
        >
    </div>

    <div class="mb-3">
        <label class="form-label">{{ __('Block link :number', ['number' => $i]) }}</label>
        <input
            class="form-control"
            name="block_link_{{ $i }}"
            type="text"
            value="{{ Arr::get($attributes, 'block_link_' . $i) }}"
            placeholder="{{ __('Block link :number', ['number' => $i]) }}"
        >
    </div>
@endfor
