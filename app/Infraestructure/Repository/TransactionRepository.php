<?php

declare(strict_types=1);

namespace App\Infraestructure\Repository;

use App\Domain\Entity\Transaction;
use App\Domain\Enum\TransactionOperation;
use App\Domain\Repository\TransactionRepositoryInterface;
use App\Infraestructure\Model\Transaction as TransactionModel;
use Hyperf\DbConnection\Db;

class TransactionRepository extends Repository implements TransactionRepositoryInterface
{
    public function __construct(private readonly TransactionModel $transactionModel, Db $database)
    {
        parent::__construct($database);
    }

    public function store(string $walletId, float $amount, TransactionOperation $operation): Transaction
    {
        $transactionModel = $this->transactionModel::create(
            [
                'wallet_id' => $walletId,
                'value' => $amount,
                'operation' => $operation->value,
            ]
        );

        return Transaction::fromArray($transactionModel->toArray());
    }
}
