<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class BaseRequest extends FormRequest
{
    protected function failedValidation(Validator $validator): void {
        $response = new JsonResponse([
            'errors' => $validator->getMessageBag()->all(),
        ], 422);

        throw new ValidationException($validator, $response);
    }
}
