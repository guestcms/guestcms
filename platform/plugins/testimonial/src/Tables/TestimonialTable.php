<?php

namespace Guestcms\Testimonial\Tables;

use Guestcms\Table\Abstracts\TableAbstract;
use Guestcms\Table\Actions\DeleteAction;
use Guestcms\Table\Actions\EditAction;
use Guestcms\Table\BulkActions\DeleteBulkAction;
use Guestcms\Table\BulkChanges\CreatedAtBulkChange;
use Guestcms\Table\BulkChanges\NameBulkChange;
use Guestcms\Table\BulkChanges\StatusBulkChange;
use Guestcms\Table\Columns\CreatedAtColumn;
use Guestcms\Table\Columns\IdColumn;
use Guestcms\Table\Columns\ImageColumn;
use Guestcms\Table\Columns\NameColumn;
use Guestcms\Table\HeaderActions\CreateHeaderAction;
use Guestcms\Testimonial\Models\Testimonial;
use Illuminate\Database\Eloquent\Builder;

class TestimonialTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(Testimonial::class)
            ->addColumns([
                IdColumn::make(),
                ImageColumn::make(),
                NameColumn::make()->route('testimonial.edit'),
                CreatedAtColumn::make(),
            ])
            ->addHeaderAction(CreateHeaderAction::make()->route('testimonial.create'))
            ->addActions([
                EditAction::make()->route('testimonial.edit'),
                DeleteAction::make()->route('testimonial.destroy'),
            ])
            ->addBulkAction(DeleteBulkAction::make()->permission('testimonial.destroy'))
            ->addBulkChanges([
                NameBulkChange::make(),
                StatusBulkChange::make(),
                CreatedAtBulkChange::make(),
            ])
            ->queryUsing(function (Builder $query) {
                return $query
                    ->select([
                        'id',
                        'name',
                        'created_at',
                        'image',
                    ]);
            });
    }
}
