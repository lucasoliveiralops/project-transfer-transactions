<?php

namespace App\Repository\Interface;

use App\Model\Wallet;
use Hyperf\Database\Model\Builder;

interface WalletRepositoryInterface
{
    public function getByUserId(string $userId): Wallet|Builder|null;

    public function updateBalanceByUserId(string $userId, float $newBalance): bool;
}