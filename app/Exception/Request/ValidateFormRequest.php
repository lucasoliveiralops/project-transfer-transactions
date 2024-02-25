<?php

namespace App\Exception\Request;

use Hyperf\HttpMessage\Exception\UnprocessableEntityHttpException;

class ValidateFormRequest extends UnprocessableEntityHttpException
{
    public function __construct(?array $data = [])
    {
        $errorEncoded = json_encode($this->getTreatedError($data));
        parent::__construct($errorEncoded);
    }

    public function getTreatedError(?array $data): array
    {
        return [
          'data' => $data,
          'message' => 'validation_fail'
        ];
    }
}