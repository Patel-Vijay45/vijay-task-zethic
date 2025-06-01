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
                'first_name' => 'required|string|max:50',
                'last_name' => 'required|string|max:50',
                'phone_no' => 'required|string|regex:/^[0-9]{10,15}$/',
                'alternative_phone_no' => 'nullable|string|regex:/^[0-9]{10,15}$/',
                'address' => 'required|string|max:255',
                'city' => 'required|string|max:50',
                'state' => 'required|string|max:50',
                'country' => 'required|string|max:50',
                'pincode' => 'required|string|max:20',
                'is_default' => 'nullable|boolean',
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
