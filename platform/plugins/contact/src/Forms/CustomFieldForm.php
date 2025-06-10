<?php

namespace Guestcms\Contact\Forms;

use Guestcms\Base\Facades\Assets;
use Guestcms\Base\Forms\FieldOptions\NameFieldOption;
use Guestcms\Base\Forms\FieldOptions\NumberFieldOption;
use Guestcms\Base\Forms\FieldOptions\OnOffFieldOption;
use Guestcms\Base\Forms\FieldOptions\SelectFieldOption;
use Guestcms\Base\Forms\FieldOptions\TextFieldOption;
use Guestcms\Base\Forms\Fields\NumberField;
use Guestcms\Base\Forms\Fields\OnOffField;
use Guestcms\Base\Forms\Fields\SelectField;
use Guestcms\Base\Forms\Fields\TextField;
use Guestcms\Base\Forms\FormAbstract;
use Guestcms\Base\Forms\MetaBox;
use Guestcms\Contact\Enums\CustomFieldType;
use Guestcms\Contact\Http\Requests\CustomFieldRequest;
use Guestcms\Contact\Models\CustomField;
use Guestcms\Language\Facades\Language;

class CustomFieldForm extends FormAbstract
{
    public function setup(): void
    {
        Assets::addScripts('jquery-ui')
            ->addScriptsDirectly('vendor/core/plugins/contact/js/custom-field.js');

        $this
            ->model(CustomField::class)
            ->formClass('custom-field-form')
            ->setValidatorClass(CustomFieldRequest::class)
            ->add(
                'type',
                SelectField::class,
                SelectFieldOption::make()
                    ->label(trans('plugins/contact::contact.custom_field.type'))
                    ->required()
                    ->choices(CustomFieldType::labels())
            )
            ->add(
                'name',
                TextField::class,
                NameFieldOption::make()
                    ->required()
            )
            ->add(
                'placeholder',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/contact::contact.custom_field.placeholder'))
                    ->placeholder(trans('plugins/contact::contact.custom_field.placeholder'))
                    ->maxLength(120)
            )
            ->add(
                'required',
                OnOffField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/contact::contact.custom_field.required'))
            )
            ->add(
                'order',
                NumberField::class,
                NumberFieldOption::make()
                    ->label(trans('plugins/contact::contact.custom_field.order'))
                    ->required()
                    ->defaultValue(999)
            )
            ->when(is_plugin_active('language'), function (FormAbstract $form): void {
                $isDefaultLanguage = ! defined('LANGUAGE_ADVANCED_MODULE_SCREEN_NAME')
                    || ! request()->input('ref_lang')
                    || request()->input('ref_lang') === Language::getDefaultLocaleCode();
                $customField = $form->getModel();
                $options = $customField->options->sortBy('order');

                $form->addMetaBox(
                    MetaBox::make('contact-custom-field-options')
                        ->hasTable()
                        ->attributes([
                            'class' => 'custom-field-options-box',
                            'style' => sprintf(
                                'display: %s;',
                                in_array(old('type', $customField), [CustomFieldType::DROPDOWN, CustomFieldType::RADIO]) ? 'block' : 'none;'
                            ),
                        ])
                        ->title(trans('plugins/contact::contact.custom_field.options'))
                        ->content(view(
                            'plugins/contact::partials.custom-field-options',
                            compact('options', 'isDefaultLanguage')
                        ))
                        ->footerContent($isDefaultLanguage ? view(
                            'plugins/contact::partials.custom-field-options-footer',
                            compact('isDefaultLanguage')
                        ) : null)
                );
            });
    }
}
