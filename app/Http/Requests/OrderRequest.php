<?php

namespace App\Http\Requests;

use App\Helpers\ResponseHelper;
use App\Models\Product;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class OrderRequest extends FormRequest
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
        return [
            'address_id' => [
                'required',
                function ($attribute, $value, $fail) {
                    $exists = \App\Models\Address::where('id', $value)
                        ->where('user_id', auth()->id())
                        ->whereNull('deleted_at')
                        ->exists();

                    if (! $exists) {
                        $fail('The selected address is invalid or does not belong to you.');
                    }
                }
            ],
            'shipping_method' => 'required',
            'shipping_description' => 'nullable',
            'is_gift' => 'nullable',
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id,deleted_at,NULL',
            'products.*.qnt' => 'required|numeric',
        ];
    }
    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            foreach ($this->input('products', []) as $index => $item) {
                $product = Product::find($item['id'] ?? 0);

                if (!$product) continue;

                if ($product->stock < $item['qnt']) {
                    $validator->errors()->add("products.$index.qnt", "Not enough stock for {$product->name}.");
                }
            }
        });
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
