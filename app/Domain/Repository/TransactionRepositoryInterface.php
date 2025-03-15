<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\Transaction;
use App\Domain\Enum\TransactionOperation;

interface TransactionRepositoryInterface
{
    public function store(string $walletId, float $amount, TransactionOperation $operation): Transaction;
}
