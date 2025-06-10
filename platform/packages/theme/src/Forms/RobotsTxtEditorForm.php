<?php

namespace Guestcms\Theme\Forms;

use Guestcms\Base\Facades\Html;
use Guestcms\Base\Forms\FieldOptions\AlertFieldOption;
use Guestcms\Base\Forms\FieldOptions\CodeEditorFieldOption;
use Guestcms\Base\Forms\Fields\AlertField;
use Guestcms\Base\Forms\Fields\CodeEditorField;
use Guestcms\Base\Forms\FormAbstract;
use Guestcms\Theme\Http\Requests\RobotsTxtRequest;
use Illuminate\Support\Facades\File;

class RobotsTxtEditorForm extends FormAbstract
{
    public function setup(): void
    {
        $isRobotsTxtWritable = File::isWritable($path = public_path('robots.txt'));
        $robotsTxtContent = $isRobotsTxtWritable && File::exists($path) ? File::get($path) : '';

        $this
            ->setUrl(route('theme.robots-txt.post'))
            ->setValidatorClass(RobotsTxtRequest::class)
            ->setActionButtons(view('core/base::forms.partials.form-actions', ['onlySave' => true])->render())
            ->when(! $isRobotsTxtWritable, function (FormAbstract $form) use ($path): void {
                $form->add(
                    'robots_txt_not_writable',
                    AlertField::class,
                    AlertFieldOption::make()
                        ->type('warning')
                        ->content(trans('packages/theme::theme.robots_txt_not_writable', ['path' => $path]))
                );
            })
            ->add(
                'robots_txt_content',
                CodeEditorField::class,
                CodeEditorFieldOption::make()
                    ->label(trans('packages/theme::theme.robots_txt_content'))
                    ->value($robotsTxtContent)
                    ->maxLength(2500)
                    ->helperText(
                        trans(
                            'packages/theme::theme.robots_txt_content_helper',
                            ['link' => Html::link(url('robots.txt'), attributes: ['target' => '_blank'])]
                        )
                    )
            )
            ->add(
                'robots_txt_file',
                'file',
                [
                    'label' => trans('packages/theme::theme.robots_txt_file'),
                    'help_block' => [
                        'text' => trans('packages/theme::theme.robots_txt_file_helper'),
                    ],
                ]
            );
        ;
    }
}
