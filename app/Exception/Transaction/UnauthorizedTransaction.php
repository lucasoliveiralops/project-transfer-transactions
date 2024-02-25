<?php

declare(strict_types=1);

namespace App\Exception\Transaction;

use Exception;

class UnauthorizedTransaction extends Exception
{
    public function __construct()
    {
        parent::__construct("Transaction was not authorized, please try again!");
    }
}
