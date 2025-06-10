<section>
    <div class="mb-3">
        <label class="form-label">{{ __('Name') }}</label>
        <input
            class="form-control"
            name="name"
            type="text"
            value="{{ Arr::get($attributes, 'name') }}"
            placeholder="{{ __('Name') }}"
        >
    </div>

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
        <input
            class="form-control"
            name="description"
            type="text"
            value="{{ Arr::get($attributes, 'description') }}"
            placeholder="{{ __('Description') }}"
        >
    </div>

    <div class="mb-3">
        <label class="form-label">{{ __('Image') }}</label>
        {!! Form::mediaImage('image', Arr::get($attributes, 'image')) !!}
    </div>

</section>
