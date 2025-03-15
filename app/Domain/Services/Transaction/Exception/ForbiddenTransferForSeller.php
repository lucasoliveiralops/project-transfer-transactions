<?php

declare(strict_types=1);

namespace App\Domain\Services\Transaction\Exception;

use Exception;

class ForbiddenTransferForSeller extends Exception
{
    public function __construct()
    {
        parent::__construct('The seller cannot make payments');
    }
}
