<?php

declare(strict_types=1);

namespace App\Domain\Event;

use App\Domain\Entity\User;

class TransferCompleted
{
    public function __construct(
        public User $payer,
        public User $payee,
        public float $amount
    ) {
    }
}
