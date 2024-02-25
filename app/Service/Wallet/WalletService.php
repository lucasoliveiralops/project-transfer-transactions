<?php

declare(strict_types=1);

namespace App\Service\Wallet;

use App\Enum\TransactionOperation;
use App\Model\User;
use App\Repository\Interface\WalletRepositoryInterface;

class WalletService
{
    public function __construct(private readonly WalletRepositoryInterface $walletRepository)
    {}

    public function hasAmount(User $user, float $amount): bool
    {
        if($this->currentBalanceByUser($user) < $amount){
            return false;
        }

        return true;
    }

    public function changeBalance(User $user, float $amount, TransactionOperation $operation): bool
    {
        if($operation == TransactionOperation::Loss){
            $newBalance = $this->currentBalanceByUser($user) - $amount;

            return $this->walletRepository->updateBalanceByUserId($user->id, $newBalance);
        }

        if($operation == TransactionOperation::Earned){
            $newBalance = $this->currentBalanceByUser($user) + $amount;

            return $this->walletRepository->updateBalanceByUserId($user->id, $newBalance);
        }

        return false;
    }

    private function currentBalanceByUser(User $user): ?float
    {
        return $this->walletRepository->getByUserId($user->id)?->current_balance;
    }
}
