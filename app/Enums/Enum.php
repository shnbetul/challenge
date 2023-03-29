<?php

namespace App\Enums;

use ReflectionClass;

abstract class Enum
{
    /**
     * Get defined constants.
     *
     * @return array<string, int|string> $constants
     */
    public static function all(): array
    {
        return (new ReflectionClass(static::class))->getConstants();
    }

    /**
     * Get defined constants to values.
     *
     * @return array<int, int|string> $values
     */
    public static function values(): array
    {
        return array_values(self::all());
    }

    /**
     * Get random constant value.
     *
     * @return int|string $randomValue
     */
    public static function randomValue(): int|string
    {
        $values = self::values();

        return $values[rand(0, count($values) - 1)];
    }
}
