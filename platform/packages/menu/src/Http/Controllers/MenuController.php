<?php

namespace Guestcms\Menu\Http\Controllers;

use Guestcms\Base\Events\CreatedContentEvent;
use Guestcms\Base\Http\Actions\DeleteResourceAction;
use Guestcms\Base\Http\Controllers\BaseController;
use Guestcms\Base\Supports\Breadcrumb;
use Guestcms\Menu\Events\RenderingMenuOptions;
use Guestcms\Menu\Facades\Menu;
use Guestcms\Menu\Forms\MenuForm;
use Guestcms\Menu\Forms\MenuNodeForm;
use Guestcms\Menu\Http\Requests\MenuNodeRequest;
use Guestcms\Menu\Http\Requests\MenuRequest;
use Guestcms\Menu\Models\Menu as MenuModel;
use Guestcms\Menu\Models\MenuLocation;
use Guestcms\Menu\Models\MenuNode;
use Guestcms\Menu\Repositories\Eloquent\MenuRepository;
use Guestcms\Menu\Tables\MenuTable;
use Guestcms\Support\Services\Cache\Cache;
use Illuminate\Http\Request;
use stdClass;

class MenuController extends BaseController
{
    protected Cache $cache;

    public function __construct()
    {
        $this->cache = Cache::make(MenuRepository::class);
    }

    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('packages/theme::theme.appearance'))
            ->add(trans('packages/menu::menu.name'), route('menus.index'));
    }

    public function index(MenuTable $table)
    {
        $this->pageTitle(trans('packages/menu::menu.name'));

        return $table->renderTable();
    }

    public function create()
    {
        RenderingMenuOptions::dispatch();

        $this->pageTitle(trans('packages/menu::menu.create'));

        return MenuForm::create()->renderForm();
    }

    public function store(MenuRequest $request)
    {
        $form = MenuForm::create();

        $form
            ->saving(function (MenuForm $form) use ($request): void {
                $form
                    ->getModel()
                    ->fill($form->getRequest()->input())
                    ->save();

                /**
                 * @var MenuModel $menu
                 */
                $menu = $form->getModel();

                $this->cache->flush();
                $this->saveMenuLocations($menu, $request);
            });

        return $this
            ->httpResponse()
            ->setPreviousRoute('menus.index')
            ->setNextRoute('menus.edit', $form->getModel()->getKey())
            ->withCreatedSuccessMessage();
    }

    protected function saveMenuLocations(MenuModel $menu, Request $request): bool
    {
        $locations = $request->input('locations', []);

        MenuLocation::query()
            ->where('menu_id', $menu->getKey())
            ->whereNotIn('location', $locations)
            ->each(fn (MenuLocation $location) => $location->delete());

        foreach ($locations as $location) {
            $menuLocation = MenuLocation::query()->firstOrCreate([
                'menu_id' => $menu->getKey(),
                'location' => $location,
            ]);

            event(new CreatedContentEvent(MENU_LOCATION_MODULE_SCREEN_NAME, $request, $menuLocation));
        }

        return true;
    }

    public function edit(int|string $id)
    {
        RenderingMenuOptions::dispatch();

        $oldInputs = old();
        if ($oldInputs && $id == 0) {
            $oldObject = new stdClass();
            foreach ($oldInputs as $key => $row) {
                $oldObject->$key = $row;
            }
            $menu = $oldObject;
        } else {
            $menu = MenuModel::query()->findOrFail($id);
        }

        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $menu->name]));

        return MenuForm::createFromModel($menu)->renderForm();
    }

    public function update(MenuModel $menu, MenuRequest $request)
    {
        MenuForm::createFromModel($menu)
            ->saving(function (MenuForm $form) use ($request): void {
                $form
                    ->getModel()
                    ->fill($form->getRequest()->input())
                    ->save();

                /**
                 * @var MenuModel $menu
                 */
                $menu = $form->getModel();

                $this->saveMenuLocations($menu, $request);
            });

        $deletedNodes = ltrim((string) $request->input('deleted_nodes', ''));
        if ($deletedNodes && $deletedNodes = array_filter(explode(' ', $deletedNodes))) {
            $menu->menuNodes()->whereIn('id', $deletedNodes)->delete();
        }

        $menuNodes = Menu::recursiveSaveMenu((array) json_decode($request->input('menu_nodes'), true), $menu->getKey(), 0);

        $request->merge(['menu_nodes', json_encode($menuNodes)]);

        $this->cache->flush();

        return $this
            ->httpResponse()
            ->setPreviousRoute('menus.index')
            ->withUpdatedSuccessMessage();
    }

    public function destroy(MenuModel $menu)
    {
        return DeleteResourceAction::make($menu);
    }

    public function getNode(MenuNodeRequest $request)
    {
        $form = MenuNodeForm::create();

        $form->saving(function (MenuNodeForm $form) use ($request): void {
            /**
             * @var MenuNode $row
             */
            $row = $form->getModel();
            $row->fill($data = $request->input('data', []));
            $row = Menu::getReferenceMenuNode($data, $row);
            $row->save();
        });

        return $this
            ->httpResponse()
            ->setData([
                'html' => view('packages/menu::partials.node', ['row' => $form->getModel()])->render(),
            ])
            ->withCreatedSuccessMessage();
    }
}
