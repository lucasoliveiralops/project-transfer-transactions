<?php

declare(strict_types=1);

namespace App\Request;

class TransferRequest extends AbstractFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'value' => ['required', 'numeric'],
            'payer' => ['required', 'uuid'],
            'payee' => ['required', 'uuid'],
        ];
    }
}
