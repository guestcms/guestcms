<?php

namespace Guestcms\SeoHelper\Forms;

use Guestcms\Base\Forms\FieldOptions\HtmlFieldOption;
use Guestcms\Base\Forms\FieldOptions\MediaImageFieldOption;
use Guestcms\Base\Forms\FieldOptions\RadioFieldOption;
use Guestcms\Base\Forms\FieldOptions\TextareaFieldOption;
use Guestcms\Base\Forms\FieldOptions\TextFieldOption;
use Guestcms\Base\Forms\Fields\HtmlField;
use Guestcms\Base\Forms\Fields\MediaImageField;
use Guestcms\Base\Forms\Fields\RadioField;
use Guestcms\Base\Forms\Fields\TextareaField;
use Guestcms\Base\Forms\Fields\TextField;
use Guestcms\Base\Forms\FormAbstract;

class SeoForm extends FormAbstract
{
    public function setup(): void
    {
        $meta = $this->getModel();

        $this
            ->contentOnly()
            ->add(
                'seo_meta[seo_title]',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('packages/seo-helper::seo-helper.seo_title'))
                    ->placeholder(trans('packages/seo-helper::seo-helper.seo_title'))
                    ->maxLength(70)
                    ->allowOverLimit()
                    ->value(old('seo_meta.seo_title', $meta['seo_title']))
            )
            ->add(
                'seo_meta[seo_description]',
                TextareaField::class,
                TextareaFieldOption::make()
                    ->label(trans('packages/seo-helper::seo-helper.seo_description'))
                    ->placeholder(trans('packages/seo-helper::seo-helper.seo_description'))
                    ->rows(3)
                    ->maxLength(160)
                    ->allowOverLimit()
                    ->value(old('seo_meta.seo_description', $meta['seo_description']))
            )
            ->add(
                'meta_keywords',
                HtmlField::class,
                HtmlFieldOption::make()
                    ->content(view('packages/theme::partials.no-meta-keywords')->render())
            )
            ->add(
                'seo_meta_image',
                MediaImageField::class,
                MediaImageFieldOption::make()
                    ->label(trans('packages/seo-helper::seo-helper.seo_image'))
                    ->value(old('seo_meta_image', $meta['seo_image']))
            )
            ->add(
                'seo_meta[index]',
                RadioField::class,
                RadioFieldOption::make()
                    ->label(trans('packages/seo-helper::seo-helper.index'))
                    ->selected(old('seo_meta.index', $meta['index']))
                    ->choices([
                        'index' => trans('packages/seo-helper::seo-helper.index'),
                        'noindex' => trans('packages/seo-helper::seo-helper.noindex'),
                    ])
            );
    }
}
