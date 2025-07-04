<?php

namespace Guestcms\Base\Supports;

use BadMethodCallException;
use Guestcms\Base\Facades\BaseHelper;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use InvalidArgumentException;
use ReflectionException;
use ReflectionFunction;

class MacroableModels
{
    protected array $macros = [];

    public function getAllMacros(): array
    {
        return $this->macros;
    }

    public function addMacro(string $model, string $name, Closure $closure): void
    {
        $this->checkModelSubclass($model);

        if (! isset($this->macros[$name])) {
            $this->macros[$name] = [];
        }

        $this->macros[$name][$model] = $closure;

        $this->syncMacros($name);
    }

    protected function checkModelSubclass(string $model): void
    {
        if (! is_subclass_of($model, Model::class)) {
            throw new InvalidArgumentException(sprintf('%s must be a subclass of %s', $model, Model::class));
        }
    }

    protected function syncMacros(string $name): void
    {
        $models = $this->macros[$name] ?? [];

        Builder::macro($name, function (...$args) use ($name, $models) {
            /**
             * @var Builder $this
             */
            $class = $this->getModel()::class;

            if (! isset($models[$class])) {
                throw new BadMethodCallException(sprintf('Call to undefined method %s::%s()', $class, $name));
            }

            $closure = Closure::bind($models[$class], $this->getModel());

            return call_user_func($closure, ...$args);
        });
    }

    public function getMacro(string $name)
    {
        return Arr::get($this->macros, $name);
    }

    public function removeMacro(string $model, string $name): bool
    {
        $this->checkModelSubclass($model);

        if (isset($this->macros[$name][$model])) {
            unset($this->macros[$name][$model]);
            if (count($this->macros[$name]) == 0) {
                unset($this->macros[$name]);
            }

            $this->syncMacros($name);

            return true;
        }

        return false;
    }

    public function modelHasMacro(string $model, string $name): bool
    {
        $this->checkModelSubclass($model);

        return isset($this->macros[$name][$model]);
    }

    public function modelsThatImplement(string $name): array
    {
        if (! isset($this->macros[$name])) {
            return [];
        }

        return array_keys($this->macros[$name]);
    }

    public function macrosForModel(string $model): array
    {
        $this->checkModelSubclass($model);

        $macros = [];

        foreach ($this->macros as $macro => $models) {
            if (! in_array($model, array_keys($models))) {
                continue;
            }

            try {
                $params = (new ReflectionFunction($this->macros[$macro][$model]))->getParameters();

                $macros[$macro] = [
                    'name' => $macro,
                    'parameters' => $params,
                ];
            } catch (ReflectionException $exception) {
                BaseHelper::logError($exception);
            }
        }

        return $macros;
    }
}
