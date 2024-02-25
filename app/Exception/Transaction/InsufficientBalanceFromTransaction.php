<?php

declare(strict_types=1);

namespace App\Exception\Transaction;

use Exception;

class InsufficientBalanceFromTransaction extends Exception
{
    public function __construct()
    {
        parent::__construct('Insufficient balance for this transaction');
    }
}