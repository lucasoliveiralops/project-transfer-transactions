<?php

declare(strict_types=1);

namespace App\Service\Wallet;

use App\Enum\TransactionOperation;
use App\Model\User;
use App\Repository\Interface\WalletRepositoryInterface;
use App\Service\Transaction\TransactionService;

class WalletService
{
    public function __construct(
        private readonly WalletRepositoryInterface $walletRepository,
        private readonly TransactionService        $transactionService,
    ){}

    public function hasAmount(User $user, float $amount): bool
    {
        $balance = $this->walletRepository->getByUserId($user->id)?->current_balance;

        if($balance < $amount){
            return false;
        }

        return true;
    }

    public function changeBalance(User $user, float $amount, TransactionOperation $operation): bool
    {
        $wallet = $this->walletRepository->getByUserId($user->id);

        $newBalance = $operation == TransactionOperation::Loss ?
            $wallet?->current_balance - $amount :
            $wallet?->current_balance + $amount;

        return $this->walletRepository->updateBalanceByUserId($user->id, $newBalance)
            && !empty($this->transactionService->store($wallet?->id, $amount, $operation));
    }
}
