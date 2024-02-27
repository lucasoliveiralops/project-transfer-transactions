<?php

namespace App\Repository\Interface;

use App\Model\Wallet;
use Hyperf\Database\Query\Builder;

interface WalletRepositoryInterface
{
    public function getLockedForUpdateByUserId(string $userId): null|Wallet|Builder;

    public function updateBalance(Wallet $wallet, float $newBalance): bool;
}
