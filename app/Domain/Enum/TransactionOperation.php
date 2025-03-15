<?php

declare(strict_types=1);

namespace App\Domain\Enum;

enum TransactionOperation: string
{
    case Earned = 'Earned';
    case Loss = 'Loss';
}
