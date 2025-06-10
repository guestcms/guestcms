<div class="mb-3">
    <label class="form-label">{{ __('YouTube URL') }}</label>
    <input
        class="form-control"
        name="url"
        value="{{ Arr::get($attributes, 'url') }}"
        placeholder="https://www.youtube.com/watch?v=FN7ALfpGxiI"
    >
</div>

<div class="mb-3">
    <label class="form-label">{{ __('Background Image') }}</label>
    {!! Form::mediaImage('background_image', Arr::get($attributes, 'background_image')) !!}
</div>
