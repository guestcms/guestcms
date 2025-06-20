<?php

namespace Guestcms\Table\Actions;

class DeleteAction extends Action
{
    public static function make(string $name = 'delete'): static
    {
        return parent::make($name)
            ->label(trans('core/base::tables.delete_entry'))
            ->color('danger')
            ->icon('ti ti-trash')
            ->action('DELETE')
            ->confirmation()
            ->confirmationModalTitle(trans('core/base::tables.confirm_delete'))
            ->confirmationModalMessage(trans('core/base::tables.confirm_delete_msg'))
            ->confirmationModalButton(trans('core/base::tables.delete'));
    }
}
