<?php

declare(strict_types=1);

namespace App\Repository;

use App\Enum\TransactionOperation;
use App\Model\Transaction;
use App\Repository\Interface\TransactionRepositoryInterface;
use Hyperf\DbConnection\Db;

class TransactionRepository extends Repository implements TransactionRepositoryInterface
{
    public function __construct(private readonly Transaction $transactionModel, Db $database)
    {
        parent::__construct($database);
    }

    public function store(string $walletId, float $amount, TransactionOperation $operation): Transaction
    {
        return $this->transactionModel::create(
            [
                'wallet_id' => $walletId,
                'value' => $amount,
                'operation' => $operation->value
            ]
        );
    }
}
