<?php

namespace AlbertCht\NotionAi\Enums;

use ReflectionClass;

class BaseEnum
{
    public static function getValues(): array
    {
        $reflection = new ReflectionClass(static::class);

        return array_values($reflection->getConstants());
    }
}
