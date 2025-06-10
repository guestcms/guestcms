<?php

namespace Guestcms\Hotel\Shortcodes\Forms;

use Guestcms\Base\Forms\FieldOptions\SelectFieldOption;
use Guestcms\Base\Forms\FieldOptions\TextFieldOption;
use Guestcms\Base\Forms\Fields\SelectField;
use Guestcms\Base\Forms\Fields\TextField;
use Guestcms\Hotel\Models\Place;
use Guestcms\Shortcode\Forms\ShortcodeForm;

class ShortcodeHotelPlaceForm extends ShortcodeForm
{
    public function setup(): void
    {
        parent::setup();

        $this
            ->add(
                'title',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/hotel::hotel.shortcodes.title'))
                    ->toArray()
            )
            ->add(
                'place_ids',
                SelectField::class,
                SelectFieldOption::make()
                    ->choices(
                        Place::query()
                        ->wherePublished()
                        ->pluck('name', 'id')
                        ->toArray()
                    )
                    ->label(trans('plugins/hotel::hotel.shortcodes.choose_places'))
                    ->searchable()
                    ->multiple()
                    ->toArray()
            );
    }
}
