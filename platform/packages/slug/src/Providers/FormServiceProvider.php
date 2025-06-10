<?php

namespace Guestcms\Slug\Providers;

use Guestcms\Base\Facades\Form;
use Guestcms\Base\Supports\ServiceProvider;

class FormServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->booted(function (): void {
            Form::component('permalink', 'packages/slug::permalink', [
                'name',
                'value' => null,
                'id' => null,
                'prefix' => '',
                'preview' => false,
                'attributes' => [],
                'editable' => true,
                'model' => '',
            ]);
        });
    }
}
