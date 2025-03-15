<?php

declare(strict_types=1);

namespace App\Domain\Entity;

class Wallet
{
    private ?string $id = null;

    private float $currentBalance;

    private string $userId;

    public function getId(): string
    {
        return $this->id;
    }

    public function getCurrentBalance(): float
    {
        return $this->currentBalance;
    }

    public function setCurrentBalance(float $amount): void
    {
        $this->currentBalance = $amount;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public static function fromArray(array $data): self
    {
        $wallet = new self();

        $wallet->id = $data['id'] ?? null;
        $wallet->currentBalance = $data['current_balance'];
        $wallet->userId = $data['user_id'];

        return $wallet;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'current_balance' => $this->currentBalance,
            'user_id' => $this->userId,
        ];
    }
}
