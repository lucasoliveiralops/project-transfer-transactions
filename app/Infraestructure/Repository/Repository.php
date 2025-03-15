<?php

declare(strict_types=1);

namespace App\Infraestructure\Repository;

use App\Domain\Repository\RepositoryInterface;
use Hyperf\DbConnection\Db;

class Repository implements RepositoryInterface
{
    public function __construct(private readonly Db $database)
    {
    }

    public function beginTransaction(): void
    {
        $this->database->beginTransaction();
    }

    public function commit(): void
    {
        $this->database->commit();
    }

    public function rollback(): void
    {
        $this->database->rollback();
    }
}
