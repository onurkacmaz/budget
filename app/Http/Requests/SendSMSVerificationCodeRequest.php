<?php

namespace App\Http\Requests;

class SendSMSVerificationCodeRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'email' => 'nullable|email',
            'password' => 'nullable|min:6',
            'phone' => 'nullable|numeric|unique:users'
        ];
    }
}
