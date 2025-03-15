<?php

declare(strict_types=1);

namespace App\Domain\Services\Wallet;

use App\Domain\Enum\TransactionOperation;
use App\Domain\Repository\TransactionRepositoryInterface;
use App\Domain\Repository\WalletRepositoryInterface;
use App\Domain\Services\Wallet\Exception\InsufficientBalanceForTransaction;

class WalletService
{
    public function __construct(
        private readonly WalletRepositoryInterface $walletRepository,
        private readonly TransactionRepositoryInterface $transactionRepository,
    ) {
    }

    public function removeBalance(string $userId, float $amount): bool
    {
        $wallet = $this->walletRepository->getLockedForUpdateByUserId($userId);
        $newBalance = $wallet->getCurrentBalance() - $amount;

        if ($newBalance < 0) {
            throw new InsufficientBalanceForTransaction();
        }

        $wallet->setCurrentBalance($newBalance);

        $this->transactionRepository->store($wallet->getId(), $amount, TransactionOperation::Loss);

        return $this->walletRepository->updateBalance($wallet);
    }

    public function addBalance(string $userId, float $amount): bool
    {
        $wallet = $this->walletRepository->getLockedForUpdateByUserId($userId);

        $wallet->setCurrentBalance($wallet->getCurrentBalance() + $amount);

        $this->transactionRepository->store($wallet->getId(), $amount, TransactionOperation::Earned);

        return $this->walletRepository->updateBalance($wallet);
    }
}
