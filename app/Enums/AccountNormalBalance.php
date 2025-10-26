<?php

namespace App\Enums;

enum AccountNormalBalance: string
{
    case Debit = 'debit';
    case Credit = 'credit';

    public function multiplier(): int
    {
        return $this === self::Debit ? 1 : -1;
    }
}
