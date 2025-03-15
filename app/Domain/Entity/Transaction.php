<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Enum\TransactionOperation;

class Transaction
{
    private ?string $id;

    private float $value;

    private string $walletId;

    private TransactionOperation $operation;

    public static function fromArray(array $data): self
    {
        $transaction = new self();

        $transaction->id = $data['id'] ?? null;
        $transaction->value = $data['value'];
        $transaction->walletId = $data['wallet_id'];
        $transaction->operation = TransactionOperation::from($data['operation']);

        return $transaction;
    }
}
