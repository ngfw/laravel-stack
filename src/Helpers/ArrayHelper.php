<?php
namespace Ngfw\LaravelStack\Helpers;

class ArrayHelper
{
    public static function merge(array $original, array $new): array
    {
        foreach ($new as $key => $value) {
            if (is_array($value) && isset($original[$key]) && is_array($original[$key])) {
                $original[$key] = self::merge($original[$key], $value);
            } else {
                $original[$key] = $value;
            }
        }
        return $original;
    }
}
