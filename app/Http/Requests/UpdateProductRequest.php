<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|required|numeric|min:0',
            'stock_quantity' => 'sometimes|required|integer|min:0',
            'category_id' => 'sometimes|required|exists:categories,id',
        ];
    }

        public function messages()
        {
            return [
                'name.required' => 'The product name is required.',
                'name.string' => 'The product name must be a string.',
                'name.max' => 'The product name may not be greater than 255 characters.',
                'description.string' => 'The product description must be a string.',
                'price.required' => 'The product price is required.',
                'price.numeric' => 'The product price must be a number.',
                'price.min' => 'The product price must be at least 0.',
                'stock_quantity.required' => 'The stock quantity is required.',
                'stock_quantity.integer' => 'The stock quantity must be an integer.',
                'stock_quantity.min' => 'The stock quantity must be at least 0.',
                'category_id.required' => 'The category ID is required.',
                'category_id.exists' => 'The selected category does not exist.',
            ];
        }
}
