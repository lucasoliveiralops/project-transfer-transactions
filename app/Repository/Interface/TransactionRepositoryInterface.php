<?php

namespace App\Repository\Interface;

use App\Enum\TransactionOperation;
use App\Model\Transaction;

interface TransactionRepositoryInterface
{
    public function store(string $walletId, float $amount, TransactionOperation $operation): Transaction;
}
