<?php

declare(strict_types=1);

namespace App\Model;

use Hyperf\Database\Model\Relations\HasOne;

class User extends Model
{
    protected array $fillable = [
        'name',
        'password',
        'email'
    ];

    public function identifier(): HasOne
    {
        return $this->hasOne(
            UserIdentifier::class
        );
    }

    public function wallet(): HasOne
    {
        return $this->hasOne(
            Wallet::class
        );
    }
}
