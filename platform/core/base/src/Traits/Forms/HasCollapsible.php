<?php

namespace Guestcms\Base\Traits\Forms;

use Guestcms\Base\Forms\FormCollapse;

trait HasCollapsible
{
    /**
     * @deprecated Use `collapsible()` in FormFieldOptions::class instead.
     */
    public function addCollapsible(FormCollapse $form): static
    {
        $form->build($this);

        return $this;
    }
}
