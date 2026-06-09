<?php

namespace App\Enums;

enum PropertyStatus: string
{
    case Active    = 'active';
    case Inactive  = 'inactive';
    case Sold      = 'sold';
    case Rented    = 'rented';

    public function label(): string
    {
        return match($this) {
            self::Active   => 'Active',
            self::Inactive => 'Inactive',
            self::Sold     => 'Sold',
            self::Rented   => 'Rented',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Active   => 'green',
            self::Inactive => 'gray',
            self::Sold     => 'blue',
            self::Rented   => 'orange',
        };
    }
}
