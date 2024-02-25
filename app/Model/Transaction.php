<?php

declare(strict_types=1);

namespace App\Model;

class Transaction extends Model
{
    protected array $fillable = [
        'value',
        'operation',
        'wallet_id',
    ];
}
