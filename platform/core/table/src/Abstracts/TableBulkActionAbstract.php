<?php

namespace Guestcms\Table\Abstracts;

use Guestcms\Base\Http\Responses\BaseHttpResponse;
use Guestcms\Base\Models\BaseModel;
use Guestcms\Base\Supports\Builders\HasLabel;
use Guestcms\Base\Supports\Builders\HasPermissions;
use Guestcms\Table\Abstracts\Concerns\HasConfirmation;
use Guestcms\Table\Abstracts\Concerns\HasPriority;
use Guestcms\Table\Actions\Concerns\HasAction;
use Closure;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Stringable;

abstract class TableBulkActionAbstract implements Htmlable, Stringable
{
    use HasAction;
    use HasConfirmation;
    use HasLabel;
    use HasPermissions;
    use HasPriority;

    protected TableAbstract $table;

    protected Closure $beforeDispatch;

    protected Closure $afterDispatch;

    protected string $dispatchUrl;

    public static function make(): self
    {
        $bulkAction = app(static::class, func_get_args());

        $bulkAction->confirmation();

        return $bulkAction;
    }

    public function table(TableAbstract $abstract): static
    {
        $this->table = $abstract;

        return $this;
    }

    public function getTable(): TableAbstract
    {
        return $this->table;
    }

    public function dispatchUrl(string $url): static
    {
        $this->dispatchUrl = $url;

        return $this;
    }

    public function getDispatchUrl(): string
    {
        return $this->dispatchUrl ?? $this->getTable()->getBulkActionDispatchUrl();
    }

    public function beforeDispatch(Closure $beforeDispatch): static
    {
        $this->beforeDispatch = $beforeDispatch;

        return $this;
    }

    public function handleBeforeDispatch(BaseModel|Model $model, array $ids): void
    {
        if (isset($this->beforeDispatch)) {
            call_user_func($this->beforeDispatch, $model, $ids);
        }
    }

    public function afterDispatch(Closure $afterDispatch): static
    {
        $this->afterDispatch = $afterDispatch;

        return $this;
    }

    public function handleAfterDispatch(BaseModel|Model $model, array $ids): void
    {
        if (isset($this->afterDispatch)) {
            call_user_func($this->afterDispatch, $model, $ids);
        }
    }

    abstract public function dispatch(BaseModel|Model $model, array $ids): BaseHttpResponse;

    public function render(): string
    {
        return view('core/table::bulk-action', [
            'action' => $this,
            'table' => $this->getTable(),
        ])->render();
    }

    public function toHtml(): string
    {
        return $this->render();
    }

    public function __toString(): string
    {
        return $this->render();
    }
}
