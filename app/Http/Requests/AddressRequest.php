<?php

namespace App\Http\Requests;

use App\Helpers\ResponseHelper;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class AddressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return match (strtolower($this->method())) {
            'post' => [
                'first_name' => 'required',
                'last_name' => 'required',
                'phone_no' => 'required',
                'alternative_phone_no' => 'nullable',
                'address' => 'required',
                'city' => 'required',
                'state' => 'required',
                'country' => 'required',
                'pincode' => 'required',
                'is_default' => 'nullable',
            ],
            'put', 'patch' => [
                'first_name' => 'nullable',
                'last_name' => 'nullable',
                'phone_no' => 'nullable',
                'alternative_phone_no' => 'nullable',
                'address' => 'nullable',
                'city' => 'nullable',
                'state' => 'nullable',
                'country' => 'nullable',
                'pincode' => 'nullable',
                'is_default' => 'nullable',
            ],
        };
    }
    public function messages()
    {
        return [];
    }
    public function validated($key = null, $default = null)
    {
        $validated = parent::validated($key, $default);
        return array_filter($validated, function ($value) {
            return !is_null($value);
        });
    }
    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();

        throw new HttpResponseException(ResponseHelper::sendError('Validation Error', $errors, 422));
    }
}
