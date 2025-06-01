<?php

namespace App\Http\Requests;

use App\Helpers\ResponseHelper;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProductRequest extends FormRequest
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
        $productId = $this->route('product')?->id ?? null;
        return match (strtolower($this->method())) {
            'post' => [
                'name' => 'required',
                'sku' => 'required|unique:products,sku,NULL,id,deleted_at,NULL',
                'price' => 'required|numeric|decimal:0,2',
                'parent_id' => 'nullable',
                'stock' => 'nullable',
                'additional' => 'nullable',
                'category_id' => 'required|exists:categories,id,deleted_at,NULL,status,1',
                'images' => 'required|array',
                'images.*' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            ],
            'put', 'patch' => [
                'name' => 'nullable',
                'sku' => 'nullable|unique:products,sku,' . $productId . ',id,deleted_at,NULL',
                'price' => 'nullable',
                'parent_id' => 'nullable',
                'stock' => 'nullable',
                'additional' => 'nullable',
                'category_id' => 'nullable|exists:categories,id,deleted_at,NULL,status,1',
                'images' => 'nullable|array',
                'images.*' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            ],
        };
    }
    public function messages()
    {
        return [
            'images.*.required' => 'The images field is required.',
            'images.*.image' => 'The images field must be a file of type: jpeg, png, jpg..',
            'images.*.mimes' => 'The images field must be a file of type: jpeg, png, jpg..',
        ];
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
