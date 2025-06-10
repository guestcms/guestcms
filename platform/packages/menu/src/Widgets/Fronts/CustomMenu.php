<?php

namespace Guestcms\Menu\Widgets\Fronts;

use Guestcms\Base\Forms\FieldOptions\NameFieldOption;
use Guestcms\Base\Forms\FieldOptions\SelectFieldOption;
use Guestcms\Base\Forms\Fields\SelectField;
use Guestcms\Base\Forms\Fields\TextField;
use Guestcms\Menu\Models\Menu;
use Guestcms\Widget\AbstractWidget;
use Guestcms\Widget\Forms\WidgetForm;

class CustomMenu extends AbstractWidget
{
    public function __construct()
    {
        parent::__construct([
            'name' => __('Custom Menu'),
            'description' => __('Add a custom menu to your widget area.'),
            'menu_id' => null,
        ]);
    }

    protected function settingForm(): WidgetForm|string|null
    {
        return WidgetForm::createFromArray($this->getConfig())
            ->add('name', TextField::class, NameFieldOption::make())
            ->add(
                'menu_id',
                SelectField::class,
                SelectFieldOption::make()
                    ->label(__('Menu'))
                    ->choices(Menu::query()->pluck('name', 'slug')->all())
                    ->searchable()
            );
    }
}
