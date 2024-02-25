<?php

declare(strict_types=1);

namespace App\Model;

class UserIdentifier extends Model
{
    public bool $timestamps = false;

    protected array $fillable = [
        'identifier',
        'type',
    ];
}
