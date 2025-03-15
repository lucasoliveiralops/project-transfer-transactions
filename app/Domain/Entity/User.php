<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Enum\UserType;

class User
{
    private ?string $id;

    private string $name;

    private string $password;

    private string $email;

    private UserType $type;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function isDefaultUser(): bool
    {
        return $this->type == UserType::DefaultUser;
    }

    public function isSeller(): bool
    {
        return $this->type == UserType::Seller;
    }

    public static function fromArray(array $data): self
    {
        $user = new self();

        $user->id = $data['id'] ?? null;
        $user->name = $data['name'];
        $user->password = $data['password'];
        $user->email = $data['email'];
        $user->type = UserType::from($data['type']);

        return $user;
    }
}
