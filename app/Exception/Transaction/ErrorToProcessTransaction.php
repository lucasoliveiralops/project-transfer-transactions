<?php

declare(strict_types=1);

namespace App\Exception\Transaction;

use Exception;

class ErrorToProcessTransaction extends Exception
{
    public function __construct()
    {
        parent::__construct('Error processing transaction, please try again');
    }
}
