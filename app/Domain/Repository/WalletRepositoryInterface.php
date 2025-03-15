<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\Wallet;

interface WalletRepositoryInterface
{
    public function getLockedForUpdateByUserId(string $userId): ?Wallet;

    public function updateBalance(Wallet $wallet): bool;
}
