<?php

declare(strict_types=1);

namespace App\Service\User;

use App\Exception\User\UserNotFound;
use App\Model\User;
use App\Repository\Interface\UserRepositoryInterface;

class UserService
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    )
    {}

    public function findOrFail(string $userId): User
    {
        $user = $this->userRepository->getById($userId);

        if(empty($user)){
            throw new UserNotFound($userId);
        }

        return $user;
    }
}
