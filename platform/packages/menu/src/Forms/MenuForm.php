<?php

namespace Guestcms\Menu\Forms;

use Guestcms\Base\Facades\Assets;
use Guestcms\Base\Forms\FieldOptions\NameFieldOption;
use Guestcms\Base\Forms\FieldOptions\StatusFieldOption;
use Guestcms\Base\Forms\Fields\SelectField;
use Guestcms\Base\Forms\Fields\TextField;
use Guestcms\Base\Forms\FormAbstract;
use Guestcms\Menu\Http\Requests\MenuRequest;
use Guestcms\Menu\Models\Menu;

class MenuForm extends FormAbstract
{
    public function setup(): void
    {
        Assets::addStyles('jquery-nestable')
            ->addScripts('jquery-nestable')
            ->addScriptsDirectly('vendor/core/packages/menu/js/menu.js')
            ->addStylesDirectly('vendor/core/packages/menu/css/menu.css');

        $this
            ->model(Menu::class)
            ->setFormOption('class', 'form-save-menu')
            ->setValidatorClass(MenuRequest::class)
            ->add('name', TextField::class, NameFieldOption::make()->required()->maxLength(120))
            ->add('status', SelectField::class, StatusFieldOption::make())
            ->addMetaBoxes([
                'structure' => [
                    'wrap' => false,
                    'content' => function () {
                        /**
                         * @var Menu $menu
                         */
                        $menu = $this->getModel();

                        return view('packages/menu::menu-structure', [
                            'menu' => $menu,
                            'locations' => $menu->getKey() ? $menu->locations()->pluck('location')->all() : [],
                        ])->render();
                    },
                ],
            ])
            ->setBreakFieldPoint('status');
    }
}
