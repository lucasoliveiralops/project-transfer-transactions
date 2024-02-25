<?php

declare(strict_types=1);

namespace App\Model;

class Wallet extends Model
{
    public bool $timestamps = false;

    protected array $fillable = [
        'current_balance',
    ];
}
