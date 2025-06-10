<section>
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
            name="subtitle"
            type="text"
            value="{{ Arr::get($attributes, 'subtitle') }}"
            placeholder="{{ __('subtitle') }}"
        >
    </div>

    <div class="mb-3">
        <label class="form-label">{{ __('Right block use image or Google map ?') }}</label>
        <select
            class="select-full"
            name="block"
            required
        >
            <option
                value="map"
                @selected(Arr::get($attributes, 'block') == 'map')
            >{{ __('Google map') }}</option>
            <option
                value="image"
                @selected(Arr::get($attributes, 'block') == 'image')
            >{{ __('Image') }}</option>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">{{ __('Address for Google map') }}</label>
        <input
            class="form-control"
            name="address"
            type="text"
            value="{{ Arr::get($attributes, 'address') }}"
            placeholder="{{ __('Address') }}"
        >
    </div>

    <div class="mb-3">
        <label class="form-label">{{ __('Choose image') }}</label>
        {!! Form::mediaImage('image', Arr::get($attributes, 'image')) !!}
    </div>
</section>
