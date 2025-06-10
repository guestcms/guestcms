<div class="mb-3">
    <label class="form-label">{{ __('About us') }}</label>
    <input
        class="form-control"
        name="about_us"
        type="text"
        value="{{ Arr::get($attributes, 'about_us') }}"
        placeholder="{{ __('About us') }}"
    >
</div>

<div class="mb-3">
    <label class="form-label">{{ __('Hotline') }}</label>
    <input
        class="form-control"
        name="hotline"
        type="text"
        value="{{ Arr::get($attributes, 'hotline') }}"
        placeholder="{{ __('Hotline') }}"
    >
</div>

<div class="mb-3">
    <label class="form-label">{{ __('Address') }}</label>
    <input
        class="form-control"
        name="address"
        type="text"
        value="{{ Arr::get($attributes, 'address') }}"
        placeholder="{{ __('Address') }}"
    >
</div>

<div class="mb-3">
    <label class="form-label">{{ __('Email') }}</label>
    <input
        class="form-control"
        name="email"
        type="text"
        value="{{ Arr::get($attributes, 'email') }}"
        placeholder="{{ __('Email') }}"
    >
</div>
