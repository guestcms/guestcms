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
