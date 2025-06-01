<?php

namespace App\Http\Requests;

use App\Helpers\ResponseHelper;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CategoryRequest extends FormRequest
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
        $categoryId = $this->route('category')?->id ?? null;
        return match (strtolower($this->method())) {
            'post' => [
                'name' => 'required|unique:categories,name,NULL,id,deleted_at,NULL',
                'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
                'category_banner' => 'required|image|mimes:jpeg,png,jpg|max:2048',
                'status' => 'nullable|boolean',
                'additional' => 'nullable',
                'status' => 'nullable|boolean',
            ],
            'put', 'patch' => [
                'name' => 'nullable|unique:categories,name,' . $categoryId . ',id,deleted_at,NULL',
                'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'category_banner' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'status' => 'nullable|boolean',
                'additional' => 'nullable',
                'status' => 'nullable|boolean',
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
