<?php

declare(strict_types=1);

namespace App\Service\Wallet;

use App\Enum\TransactionOperation;
use App\Exception\Transaction\InsufficientBalanceForTransaction;
use App\Repository\Interface\WalletRepositoryInterface;
use App\Service\Transaction\TransactionService;

class WalletService
{
    public function __construct(
        private readonly WalletRepositoryInterface $walletRepository,
        private readonly TransactionService        $transactionService,
    ){}

    public function removeBalance(string $userId, float $amount): bool
    {
        $wallet = $this->walletRepository->getLockedForUpdateByUserId($userId);
        $newBalance = $wallet->current_balance - $amount;
        if($newBalance < 0) {
            throw new InsufficientBalanceForTransaction();
        }

        return $this->walletRepository->updateBalance($wallet, $newBalance)
            && !empty($this->transactionService->store($wallet->id, $amount, TransactionOperation::Loss));
    }

    public function addBalance(string $userId, float $amount): bool
    {
        $wallet = $this->walletRepository->getLockedForUpdateByUserId($userId);

        return $this->walletRepository->updateBalance($wallet, $wallet->current_balance + $amount)
            && !empty($this->transactionService->store($wallet->id, $amount, TransactionOperation::Earned));
    }
}
