<?php

namespace Guestcms\Hotel\Forms\Fronts\Auth\FieldOptions;

use Guestcms\Base\Facades\BaseHelper;

trait HasIcon
{
    public function icon(string $name): static
    {
        $this
            ->prepend(
                sprintf(
                    '<div class="position-relative"><span class="auth-input-icon input-group-text">%s</span>',
                    BaseHelper::renderIcon($name)
                )
            )
            ->append('</div>')
            ->cssClass('form-control ps-5');

        return $this;
    }
}
