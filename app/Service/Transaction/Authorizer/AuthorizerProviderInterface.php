<?php

namespace App\Service\Transaction\Authorizer;

interface AuthorizerProviderInterface
{
    public function authorize(): bool;
}