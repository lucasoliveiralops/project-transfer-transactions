<?php

namespace App\Repository\Interface;

use App\Model\User;

interface UserRepositoryInterface
{
    public function getById(string $userId): User;
}