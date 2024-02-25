<?php

namespace App\Request\Rule;

use Hyperf\Validation\Contract\Rule;

class MoneyRule implements Rule
{
    public function passes(string $attribute, mixed $value): bool
    {
        if($value <= 0) {
            return false;
        }
        if(! is_numeric($value)) {
            return false;
        }
        $decimalCases = explode('.', $value);
        if(!empty($decimalCases[1]) && strlen($decimalCases[1]) > 2){
            return false;
        }

        return true;
    }

    public function message(): array|string
    {
        return [
            'en' => 'The value is an invalid float'
        ];
    }
}