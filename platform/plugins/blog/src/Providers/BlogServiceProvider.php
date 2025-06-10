<?php

namespace Guestcms\Blog\Providers;

use Guestcms\ACL\Models\User;
use Guestcms\Api\Facades\ApiHelper;
use Guestcms\Base\Facades\DashboardMenu;
use Guestcms\Base\Facades\PanelSectionManager;
use Guestcms\Base\PanelSections\PanelSectionItem;
use Guestcms\Base\Supports\DashboardMenuItem;
use Guestcms\Base\Supports\ServiceProvider;
use Guestcms\Base\Traits\LoadAndPublishDataTrait;
use Guestcms\Blog\Models\Category;
use Guestcms\Blog\Models\Post;
use Guestcms\Blog\Models\Tag;
use Guestcms\Blog\Repositories\Eloquent\CategoryRepository;
use Guestcms\Blog\Repositories\Eloquent\PostRepository;
use Guestcms\Blog\Repositories\Eloquent\TagRepository;
use Guestcms\Blog\Repositories\Interfaces\CategoryInterface;
use Guestcms\Blog\Repositories\Interfaces\PostInterface;
use Guestcms\Blog\Repositories\Interfaces\TagInterface;
use Guestcms\DataSynchronize\PanelSections\ExportPanelSection;
use Guestcms\DataSynchronize\PanelSections\ImportPanelSection;
use Guestcms\Language\Facades\Language;
use Guestcms\LanguageAdvanced\Supports\LanguageAdvancedManager;
use Guestcms\PluginManagement\Events\DeactivatedPlugin;
use Guestcms\PluginManagement\Events\RemovedPlugin;
use Guestcms\SeoHelper\Facades\SeoHelper;
use Guestcms\Setting\PanelSections\SettingOthersPanelSection;
use Guestcms\Shortcode\View\View;
use Guestcms\Slug\Facades\SlugHelper;
use Guestcms\Slug\Models\Slug;
use Guestcms\Theme\Events\ThemeRoutingBeforeEvent;
use Guestcms\Theme\Facades\SiteMapManager;

/**
 * @since 02/07/2016 09:50 AM
 */
class BlogServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        $this->app->bind(PostInterface::class, function () {
            return new PostRepository(new Post());
        });

        $this->app->bind(CategoryInterface::class, function () {
            return new CategoryRepository(new Category());
        });

        $this->app->bind(TagInterface::class, function () {
            return new TagRepository(new Tag());
        });
    }

    public function boot(): void
    {
        $this
            ->setNamespace('plugins/blog')
            ->loadHelpers()
            ->loadAndPublishConfigurations(['permissions', 'general'])
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->loadRoutes()
            ->loadMigrations()
            ->publishAssets();

        if (class_exists('ApiHelper') && ApiHelper::enabled()) {
            $this->loadRoutes(['api']);
        }

        $this->app->register(EventServiceProvider::class);

        $this->app['events']->listen(ThemeRoutingBeforeEvent::class, function (): void {
            SiteMapManager::registerKey([
                'blog-categories',
                'blog-tags',
                'blog-posts',
            ]);

            // Register monthly archive sitemaps for blog posts
            SiteMapManager::registerMonthlyArchives('blog-posts');
        });

        SlugHelper::registering(function (): void {
            SlugHelper::registerModule(Post::class, fn () => trans('plugins/blog::base.blog_posts'));
            SlugHelper::registerModule(Category::class, fn () => trans('plugins/blog::base.blog_categories'));
            SlugHelper::registerModule(Tag::class, fn () => trans('plugins/blog::base.blog_tags'));

            SlugHelper::setPrefix(Tag::class, 'tag', true);
            SlugHelper::setPrefix(Post::class, null, true);
            SlugHelper::setPrefix(Category::class, null, true);
        });

        DashboardMenu::default()->beforeRetrieving(function (): void {
            DashboardMenu::make()
                ->registerItem(
                    DashboardMenuItem::make()
                        ->id('cms-plugins-blog')
                        ->priority(3)
                        ->name('plugins/blog::base.menu_name')
                        ->icon('ti ti-article')
                )
                ->registerItem(
                    DashboardMenuItem::make()
                        ->id('cms-plugins-blog-post')
                        ->priority(10)
                        ->parentId('cms-plugins-blog')
                        ->name('plugins/blog::posts.menu_name')
                        ->icon('ti ti-file-text')
                        ->route('posts.index')
                )
                ->registerItem(
                    DashboardMenuItem::make()
                        ->id('cms-plugins-blog-categories')
                        ->priority(20)
                        ->parentId('cms-plugins-blog')
                        ->name('plugins/blog::categories.menu_name')
                        ->icon('ti ti-folder')
                        ->route('categories.index')
                )
                ->registerItem(
                    DashboardMenuItem::make()
                        ->id('cms-plugins-blog-tags')
                        ->priority(30)
                        ->parentId('cms-plugins-blog')
                        ->name('plugins/blog::tags.menu_name')
                        ->icon('ti ti-tag')
                        ->route('tags.index')
                );
        });

        PanelSectionManager::default()->beforeRendering(function (): void {
            PanelSectionManager::registerItem(
                SettingOthersPanelSection::class,
                fn () => PanelSectionItem::make('blog')
                    ->setTitle(trans('plugins/blog::base.settings.title'))
                    ->withIcon('ti ti-file-settings')
                    ->withDescription(trans('plugins/blog::base.settings.description'))
                    ->withPriority(120)
                    ->withRoute('blog.settings')
            );
        });

        PanelSectionManager::setGroupId('data-synchronize')->beforeRendering(function (): void {
            PanelSectionManager::default()
                ->registerItem(
                    ExportPanelSection::class,
                    fn () => PanelSectionItem::make('posts')
                        ->setTitle(trans('plugins/blog::posts.posts'))
                        ->withDescription(trans('plugins/blog::posts.export.description'))
                        ->withPriority(999)
                        ->withPermission('posts.export')
                        ->withRoute('tools.data-synchronize.export.posts.index')
                )
                ->registerItem(
                    ImportPanelSection::class,
                    fn () => PanelSectionItem::make('posts')
                        ->setTitle(trans('plugins/blog::posts.posts'))
                        ->withDescription(trans('plugins/blog::posts.import.description'))
                        ->withPriority(999)
                        ->withPermission('posts.import')
                        ->withRoute('tools.data-synchronize.import.posts.index')
                );
        });

        if (defined('LANGUAGE_MODULE_SCREEN_NAME') && defined('LANGUAGE_ADVANCED_MODULE_SCREEN_NAME')) {
            if (
                defined('LANGUAGE_ADVANCED_MODULE_SCREEN_NAME') &&
                $this->app['config']->get('plugins.blog.general.use_language_v2')
            ) {
                LanguageAdvancedManager::registerModule(Post::class, [
                    'name',
                    'description',
                    'content',
                ]);

                LanguageAdvancedManager::registerModule(Category::class, [
                    'name',
                    'description',
                ]);

                LanguageAdvancedManager::registerModule(Tag::class, [
                    'name',
                    'description',
                ]);
            } else {
                Language::registerModule([Post::class, Category::class, Tag::class]);
            }
        }

        User::resolveRelationUsing('posts', function (User $user) {
            return $user->morphMany(Post::class, 'author');
        });

        User::resolveRelationUsing('slugable', function (User $user) {
            return $user->morphMany(Slug::class, 'reference');
        });

        $this->app->booted(function (): void {
            SeoHelper::registerModule([Post::class, Category::class, Tag::class]);

            $configKey = 'packages.revision.general.supported';
            config()->set($configKey, array_merge(config($configKey, []), [Post::class]));

            $this->app->register(HookServiceProvider::class);
        });

        if (function_exists('shortcode')) {
            view()->composer([
                'plugins/blog::themes.post',
                'plugins/blog::themes.category',
                'plugins/blog::themes.tag',
            ], function (View $view): void {
                $view->withShortcodes();
            });
        }

        $this->app['events']->listen(
            [DeactivatedPlugin::class, RemovedPlugin::class],
            function (DeactivatedPlugin|RemovedPlugin $event): void {
                if ($event->plugin === 'member') {
                    Post::query()->where('author_type', 'Guestcms\Member\Models\Member')->update([
                        'author_id' => null,
                        'author_type' => User::class,
                    ]);
                }
            }
        );
    }
}
