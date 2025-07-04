<?php

namespace Guestcms\Base\Supports;

use BadMethodCallException;
use Guestcms\Base\Facades\BaseHelper;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\HtmlString;
use JsonSerializable;
use ReflectionClass;
use ReflectionException;

abstract class Enum implements CastsAttributes, JsonSerializable
{
    protected static array $cache = [];

    protected static $langPath = 'core/base::enums';

    protected mixed $value = null;

    final public function __construct()
    {
    }

    public function make($value): static
    {
        if ($value instanceof static) {
            $this->value = $value->getValue();

            return $this;
        }

        if ($value !== null && ! $this->isValid($value)) {
            Log::error(sprintf('Value %s is not part of the enum %s', json_encode($value), static::class));
        } else {
            $this->value = $value;
        }

        return $this;
    }

    public function getValue()
    {
        return $this->value;
    }

    /**
     * Check if is valid enum value
     */
    public static function isValid($value): bool
    {
        return in_array($value, static::toArray(), true);
    }

    public static function toArray(bool $includeDefault = false): array
    {
        $class = static::class;
        if (! isset(static::$cache[$class])) {
            try {
                $reflection = new ReflectionClass($class);
                static::$cache[$class] = $reflection->getConstants();
            } catch (ReflectionException $exception) {
                BaseHelper::logError($exception);
            }
        }

        $result = static::$cache[$class];

        if (isset($result['__default']) && ! $includeDefault) {
            unset($result['__default']);
        }

        return apply_filters(BASE_FILTER_ENUM_ARRAY, $result, static::class);
    }

    /**
     * Returns the names (keys) of all constants in the Enum class
     */
    public static function keys(): array
    {
        return array_keys(static::toArray());
    }

    /**
     * Returns instances of the Enum class of all Enum constants
     *
     * @return static[] Constant name in key, Enum instance in value
     */
    public static function values(): array
    {
        $values = [];

        foreach (static::toArray() as $key => $value) {
            $values[$key] = (new static())->make($value);
        }

        return $values;
    }

    /**
     * Returns a value when called statically like so: MyEnum::SOME_VALUE() given SOME_VALUE is a class constant
     *
     *
     * @return static
     *
     * @throws BadMethodCallException
     */
    public static function __callStatic(string $name, array $arguments)
    {
        $array = static::toArray();

        if (isset($array[$name]) || array_key_exists($name, $array)) {
            return (new static())->make($array[$name]);
        }

        throw new BadMethodCallException('No static method or enum constant ' . $name . ' in class ' . static::class);
    }

    public static function labels(): array
    {
        $result = [];

        foreach (static::toArray() as $value) {
            $result[$value] = static::getLabel($value);
        }

        return $result;
    }

    public static function getLabel(?string $value): ?string
    {
        $key = sprintf(
            '%s.%s',
            static::$langPath,
            $value
        );

        $label = Lang::has($key) ? trans($key) : $value;

        return apply_filters(BASE_FILTER_ENUM_LABEL, $label, static::class);
    }

    /**
     * Returns the enum key (i.e. the constant name).
     *
     * @return false|int|string
     */
    public function getKey(): bool|int|string
    {
        return static::search($this->value);
    }

    /**
     * Return key for value
     *
     * @param  string|int  $value
     * @return false|int|string
     */
    public static function search($value): bool|int|string
    {
        return array_search($value, static::toArray(), true);
    }

    public function __toString()
    {
        return (string) $this->value;
    }

    /**
     * Compares one Enum with another.
     *
     * @return bool True if Enums are equal, false if not equal
     */
    final public function equals(?Enum $enum = null): bool
    {
        return $enum !== null && $this->getValue() === $enum->getValue() && static::class === $enum::class;
    }

    /**
     * Specify data which should be serialized to JSON. This method returns data that can be serialized by json_encode()
     * natively.
     *
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     */
    public function jsonSerialize(): array
    {
        return [
            'value' => $this->getValue(),
            'label' => $this->label(),
        ];
    }

    public function label(): ?string
    {
        return self::getLabel($this->getValue());
    }

    public function toHtml()
    {
        return new HtmlString(apply_filters(BASE_FILTER_ENUM_HTML, $this->value, get_called_class()));
    }

    public function get($model, string $key, $value, array $attributes): self
    {
        return $this->asEnum($value);
    }

    public function set($model, string $key, $value, array $attributes): self
    {
        return $this->asEnum($value);
    }

    protected function asEnum($value): Enum
    {
        if ($value instanceof Enum) {
            return $value;
        }

        return (new static())->make($value);
    }
}
