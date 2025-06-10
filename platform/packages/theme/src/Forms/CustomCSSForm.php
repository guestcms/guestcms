<?php

namespace Guestcms\Theme\Forms;

use Guestcms\Base\Facades\BaseHelper;
use Guestcms\Base\Forms\FieldOptions\CodeEditorFieldOption;
use Guestcms\Base\Forms\Fields\CodeEditorField;
use Guestcms\Base\Forms\FormAbstract;
use Guestcms\Theme\Facades\Theme;
use Guestcms\Theme\Http\Requests\CustomCssRequest;
use Illuminate\Support\Facades\File;

class CustomCSSForm extends FormAbstract
{
    public function setup(): void
    {
        $css = null;
        $file = Theme::getStyleIntegrationPath();

        if (File::exists($file)) {
            $css = BaseHelper::getFileData($file, false);
        }

        $this
            ->setUrl(route('theme.custom-css.post'))
            ->setValidatorClass(CustomCssRequest::class)
            ->setActionButtons(view('core/base::forms.partials.form-actions', ['onlySave' => true])->render())
            ->add(
                'custom_css',
                CodeEditorField::class,
                CodeEditorFieldOption::make()
                    ->label(trans('packages/theme::theme.custom_css'))
                    ->value($css)
                    ->mode('css')
                    ->maxLength(100000)
            );
    }
}
