<?php

namespace Guestcms\Setting\Forms;

use Guestcms\Base\Forms\FieldOptions\CheckboxFieldOption;
use Guestcms\Base\Forms\FieldOptions\SelectFieldOption;
use Guestcms\Base\Forms\Fields\OnOffCheckboxField;
use Guestcms\Base\Forms\Fields\SelectField;
use Guestcms\Setting\Http\Requests\DataTableSettingRequest;

class DataTableSettingForm extends SettingForm
{
    public function setup(): void
    {
        parent::setup();

        $this
            ->setSectionTitle(trans('core/setting::setting.datatable.title'))
            ->setSectionDescription(trans('core/setting::setting.datatable.description'))
            ->setValidatorClass(DataTableSettingRequest::class)
            ->add(
                'datatables_pagination_type',
                SelectField::class,
                SelectFieldOption::make()
                    ->choices([
                        null => trans('core/setting::setting.datatable.form.default'),
                        'dropdown' => trans('core/setting::setting.datatable.form.dropdown'),
                    ])
                    ->selected(setting('datatables_pagination_type'))
                    ->label(trans('core/setting::setting.datatable.form.pagination_type'))
            )
            ->add(
                'datatables_default_show_column_visibility',
                OnOffCheckboxField::class,
                CheckboxFieldOption::make()
                    ->label(trans('core/setting::setting.datatable.form.show_column_visibility'))
                    ->value(setting('datatables_default_show_column_visibility', false))
            )
            ->add(
                'datatables_default_show_export_button',
                OnOffCheckboxField::class,
                CheckboxFieldOption::make()
                    ->label(trans('core/setting::setting.datatable.form.show_export_button'))
                    ->value(setting('datatables_default_show_export_button', false))
            );
    }
}
