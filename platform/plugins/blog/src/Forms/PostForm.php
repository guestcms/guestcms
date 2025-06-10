<?php

namespace Guestcms\Blog\Forms;

use Guestcms\Base\Forms\FieldOptions\ContentFieldOption;
use Guestcms\Base\Forms\FieldOptions\DescriptionFieldOption;
use Guestcms\Base\Forms\FieldOptions\IsFeaturedFieldOption;
use Guestcms\Base\Forms\FieldOptions\MediaImageFieldOption;
use Guestcms\Base\Forms\FieldOptions\NameFieldOption;
use Guestcms\Base\Forms\FieldOptions\RadioFieldOption;
use Guestcms\Base\Forms\FieldOptions\SelectFieldOption;
use Guestcms\Base\Forms\FieldOptions\StatusFieldOption;
use Guestcms\Base\Forms\FieldOptions\TagFieldOption;
use Guestcms\Base\Forms\Fields\EditorField;
use Guestcms\Base\Forms\Fields\MediaImageField;
use Guestcms\Base\Forms\Fields\OnOffField;
use Guestcms\Base\Forms\Fields\RadioField;
use Guestcms\Base\Forms\Fields\SelectField;
use Guestcms\Base\Forms\Fields\TagField;
use Guestcms\Base\Forms\Fields\TextareaField;
use Guestcms\Base\Forms\Fields\TextField;
use Guestcms\Base\Forms\Fields\TreeCategoryField;
use Guestcms\Base\Forms\FormAbstract;
use Guestcms\Blog\Http\Requests\PostRequest;
use Guestcms\Blog\Models\Category;
use Guestcms\Blog\Models\Post;
use Guestcms\Blog\Models\Tag;

class PostForm extends FormAbstract
{
    public function setup(): void
    {
        $this
            ->model(Post::class)
            ->setValidatorClass(PostRequest::class)
            ->add('name', TextField::class, NameFieldOption::make()->required())
            ->add('description', TextareaField::class, DescriptionFieldOption::make())
            ->add(
                'is_featured',
                OnOffField::class,
                IsFeaturedFieldOption::make()
            )
            ->add('content', EditorField::class, ContentFieldOption::make()->allowedShortcodes())
            ->add('status', SelectField::class, StatusFieldOption::make())
            ->when(get_post_formats(true), function (PostForm $form, array $postFormats): void {
                if (count($postFormats) > 1) {
                    $choices = [];

                    foreach ($postFormats as $postFormat) {
                        $choices[$postFormat[0]] = $postFormat[1];
                    }

                    $form
                        ->add(
                            'format_type',
                            RadioField::class,
                            RadioFieldOption::make()
                                ->label(trans('plugins/blog::posts.form.format_type'))
                                ->choices($choices)
                        );
                }
            })
            ->add(
                'categories[]',
                TreeCategoryField::class,
                SelectFieldOption::make()
                    ->label(trans('plugins/blog::posts.form.categories'))
                    ->choices(function () {
                        return Category::query()
                            ->wherePublished()
                            ->select(['id', 'name', 'parent_id'])
                            ->with('activeChildren')
                            ->where('parent_id', 0)
                            ->get();
                    })
                    ->when($this->getModel()->getKey(), function (SelectFieldOption $fieldOption) {
                        /**
                         * @var Post $post
                         */
                        $post = $this->getModel();

                        return $fieldOption->selected($post->categories()->pluck('category_id')->all());
                    }, function (SelectFieldOption $fieldOption) {
                        return $fieldOption
                            ->selected(
                                Category::query()
                                    ->wherePublished()
                                    ->where('is_default', 1)
                                    ->pluck('id')
                                    ->all()
                            );
                    })
            )
            ->add('image', MediaImageField::class, MediaImageFieldOption::make())
            ->add(
                'tag',
                TagField::class,
                TagFieldOption::make()
                    ->label(trans('plugins/blog::posts.form.tags'))
                    ->when($this->getModel()->getKey(), function (TagFieldOption $fieldOption) {
                        /**
                         * @var Post $post
                         */
                        $post = $this->getModel();

                        return $fieldOption
                            ->selected(
                                $post
                                    ->tags()
                                    ->select('name')
                                    ->get()
                                    ->map(fn (Tag $item) => $item->name)
                                    ->implode(',')
                            );
                    })
                    ->placeholder(trans('plugins/blog::base.write_some_tags'))
                    ->ajaxUrl(route('tags.all'))
            )
            ->setBreakFieldPoint('status');
    }
}
