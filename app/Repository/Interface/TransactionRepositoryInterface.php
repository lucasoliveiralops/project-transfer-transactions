<?php

namespace App\Repository\Interface;

use App\Enum\TransactionOperation;
use App\Model\Transaction;
use App\Model\User;

interface TransactionRepositoryInterface
{
    public function store(string $walletId, float $amount, TransactionOperation $operation): Transaction;
}
