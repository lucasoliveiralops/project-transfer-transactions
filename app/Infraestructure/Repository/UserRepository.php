<?php

declare(strict_types=1);

namespace App\Infraestructure\Repository;

use App\Domain\Entity\User;
use App\Domain\Repository\UserRepositoryInterface;
use App\Infraestructure\Model\User as UserModel;
use Hyperf\DbConnection\Db;

class UserRepository extends Repository implements UserRepositoryInterface
{
    public function __construct(
        private readonly UserModel $userModel,
        Db $database
    ) {
        parent::__construct($database);
    }

    public function getById(string $userId): ?User
    {
        $userModel = $this->userModel->find($userId);

        if (! $userModel) {
            return null;
        }

        return User::fromArray($userModel->toArray());
    }
}
