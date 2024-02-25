<?php

declare(strict_types=1);

namespace App\Enum;

enum TransactionOperation: string
{
    case Earned = 'Earned';
    case Loss = 'Loss';
}
