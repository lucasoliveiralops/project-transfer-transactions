<?php

declare(strict_types=1);

namespace App\Domain\Enum;

enum UserType: string
{
    case Seller = 'Seller';
    case DefaultUser = 'DefaultUser';
}
