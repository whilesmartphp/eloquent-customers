<?php

namespace Whilesmart\Customers\Enums;

enum CustomerType: string
{
    case Individual = 'individual';
    case Organization = 'organization';

    public static function values(): array
    {
        return array_map(fn (self $t) => $t->value, self::cases());
    }
}
