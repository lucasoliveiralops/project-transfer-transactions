<?php

declare(strict_types=1);

namespace App\Exception\User;

use Exception;

class UserNotFound extends Exception
{
    public function __construct(string $userId)
    {
        parent::__construct("User $userId not found");
    }
}
