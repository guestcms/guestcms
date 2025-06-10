<?php

namespace Guestcms\Hotel\Shortcodes\Forms;

use Guestcms\Base\Forms\FieldOptions\SelectFieldOption;
use Guestcms\Base\Forms\FieldOptions\TextFieldOption;
use Guestcms\Base\Forms\Fields\SelectField;
use Guestcms\Base\Forms\Fields\TextField;
use Guestcms\Hotel\Models\Service;
use Guestcms\Shortcode\Forms\ShortcodeForm;

class ShortcodeHotelServiceForm extends ShortcodeForm
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
                'service_ids',
                SelectField::class,
                SelectFieldOption::make()
                    ->choices(
                        Service::query()
                        ->wherePublished()
                        ->pluck('name', 'id')
                        ->toArray()
                    )
                    ->label(trans('plugins/hotel::hotel.shortcodes.choose_services'))
                    ->searchable()
                    ->multiple()
                    ->toArray()
            );
    }
}
