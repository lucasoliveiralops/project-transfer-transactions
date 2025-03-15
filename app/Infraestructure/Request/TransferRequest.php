<?php

declare(strict_types=1);

namespace App\Infraestructure\Request;

use App\Infraestructure\Request\Rule\MoneyRule;

class TransferRequest extends AbstractFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'value' => ['required', 'numeric', new MoneyRule()],
            'payer' => ['required', 'uuid'],
            'payee' => ['required', 'uuid'],
        ];
    }
}
