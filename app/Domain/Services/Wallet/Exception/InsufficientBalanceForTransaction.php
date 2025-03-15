<?php

declare(strict_types=1);

namespace App\Domain\Services\Wallet\Exception;

use Exception;

class InsufficientBalanceForTransaction extends Exception
{
    public function __construct()
    {
        parent::__construct('Insufficient balance for this transaction');
    }
}
