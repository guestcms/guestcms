@foreach ($rooms as $room)
    <div class="mb-3">
        <label class="form-label">{{ __('Icon for :room', ['room' => $room->name]) }}</label>
        {!! Form::themeIcon('icon_room_' . $room->id, Arr::get($attributes, 'icon_room_' . $room->id)) !!}
    </div>
@endforeach
