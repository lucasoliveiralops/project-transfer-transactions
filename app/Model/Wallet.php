<?php

declare(strict_types=1);

namespace App\Model;

use Hyperf\Database\Model\Relations\HasMany;

class Wallet extends Model
{
    public bool $timestamps = false;

    protected array $fillable = [
        'current_balance',
    ];

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}
