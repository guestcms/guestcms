<?php

namespace Guestcms\Revision;

class FieldFormatter
{
    public static function format(string $key, ?string $value, array $formats): ?string
    {
        foreach ($formats as $pkey => $format) {
            $parts = explode(':', $format);
            if (count($parts) === 1) {
                continue;
            }

            if ($pkey == $key) {
                $method = array_shift($parts);

                if (method_exists(get_class(), $method)) {
                    return self::$method($value, implode(':', $parts));
                }

                break;
            }
        }

        return $value;
    }
}
