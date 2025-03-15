<?php

declare(strict_types=1);

namespace App\Infraestructure\Model;

use Hyperf\Database\Model\Relations\HasMany;

class Wallet extends Model
{
    public bool $timestamps = false;

    protected array $fillable = [
        'id',
        'user_id',
        'current_balance',
    ];

    protected array $casts = [
        'current_balance' => 'float',
    ];

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}
