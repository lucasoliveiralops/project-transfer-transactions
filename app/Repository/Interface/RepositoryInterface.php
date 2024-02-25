<?php

namespace App\Repository\Interface;

interface RepositoryInterface
{
    public function beginTransaction(): void;

    public function commit(): void;

    public function rollback(): void;
}
