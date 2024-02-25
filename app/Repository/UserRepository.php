<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\User;
use App\Repository\Interface\UserRepositoryInterface;
use Hyperf\DbConnection\Db;

class UserRepository extends Repository implements UserRepositoryInterface
{
    public function __construct(private readonly User $userModel, Db $database)
    {
        parent::__construct($database);
    }

    public function getById(string $userId): ?User
    {
        return $this->userModel->find($userId); 
    }
}
