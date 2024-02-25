<?php

namespace App\Repository\Interface;

use App\Model\User;

interface RepositoryInterface
{
    public function beginTransaction(): void;

    public function commit(): void;

    public function rollback(): void;
}
