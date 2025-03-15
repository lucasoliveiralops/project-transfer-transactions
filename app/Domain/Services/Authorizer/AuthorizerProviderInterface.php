<?php

declare(strict_types=1);

namespace App\Domain\Services\Authorizer;

interface AuthorizerProviderInterface
{
    public function authorize(): bool;
}
