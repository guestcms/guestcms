<?php

namespace Guestcms\Page\Forms;

use Guestcms\Base\Forms\FieldOptions\ContentFieldOption;
use Guestcms\Base\Forms\FieldOptions\DescriptionFieldOption;
use Guestcms\Base\Forms\FieldOptions\MediaImageFieldOption;
use Guestcms\Base\Forms\FieldOptions\NameFieldOption;
use Guestcms\Base\Forms\FieldOptions\SelectFieldOption;
use Guestcms\Base\Forms\FieldOptions\StatusFieldOption;
use Guestcms\Base\Forms\Fields\EditorField;
use Guestcms\Base\Forms\Fields\MediaImageField;
use Guestcms\Base\Forms\Fields\SelectField;
use Guestcms\Base\Forms\Fields\TextareaField;
use Guestcms\Base\Forms\Fields\TextField;
use Guestcms\Base\Forms\FormAbstract;
use Guestcms\Page\Http\Requests\PageRequest;
use Guestcms\Page\Models\Page;
use Guestcms\Page\Supports\Template;

class PageForm extends FormAbstract
{
    public function setup(): void
    {
        $this
            ->model(Page::class)
            ->setValidatorClass(PageRequest::class)
            ->add('name', TextField::class, NameFieldOption::make()->maxLength(120)->required())
            ->add('description', TextareaField::class, DescriptionFieldOption::make())
            ->add('content', EditorField::class, ContentFieldOption::make()->allowedShortcodes())
            ->add('status', SelectField::class, StatusFieldOption::make())
            ->when(Template::getPageTemplates(), function (PageForm $form, array $templates) {
                return $form
                    ->add(
                        'template',
                        SelectField::class,
                        SelectFieldOption::make()
                            ->label(trans('core/base::forms.template'))
                            ->required()
                            ->choices($templates)
                    );
            })
            ->add('image', MediaImageField::class, MediaImageFieldOption::make())
            ->setBreakFieldPoint('status');
    }
}
