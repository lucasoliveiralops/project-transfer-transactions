<?php

namespace App\Event;

use App\Model\User;

class TransferCompleted
{
    public function __construct(
        public User $payer,
        public User $payee,
        public float $amount
    ){}
}