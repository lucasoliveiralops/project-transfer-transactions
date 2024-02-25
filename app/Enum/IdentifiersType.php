<?php

declare(strict_types=1);

namespace App\Enum;

enum IdentifiersType: string
{
    case CPF = 'CPF';
    case CNPJ = 'CNPJ';
}
