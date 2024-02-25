<?php

declare(strict_types=1);

namespace App\Request;

use App\Exception\Request\ValidateFormRequest;
use Hyperf\Validation\Contract\ValidatesWhenResolved;
use Hyperf\Validation\Request\FormRequest;
use Hyperf\Validation\ValidationException;

class AbstractFormRequest extends FormRequest implements ValidatesWhenResolved
{
    public function validateResolved(): void
    {
        try {
            $validator = $this->getValidatorInstance();

            if($validator->fails()){
                throw new ValidateFormRequest($validator->errors()->all());
            }
        } catch (ValidationException $exception){
            throw new ValidateFormRequest($exception->validator->errors()->all());
        }
    }
}
