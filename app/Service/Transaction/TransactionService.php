<?php

declare(strict_types=1);

namespace App\Service\Transaction;

use App\Enum\TransactionOperation;
use App\Model\Transaction;
use App\Repository\Interface\TransactionRepositoryInterface;

class TransactionService
{
    public function __construct(
        private readonly TransactionRepositoryInterface $repository,
    )
    {}

    public function store(string $walletId, float $amount, TransactionOperation $operation): Transaction
    {
        return $this->repository->store($walletId, $amount, $operation);
    }
}
