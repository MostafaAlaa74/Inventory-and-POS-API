<?php

namespace App\Http\Requests;

use App\Rules\CheckStockRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateOrderRequest extends FormRequest
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
            'customer_id' => 'nullable|exists:customers,id',
            'payment_method' => 'required|in:cash,visa',
            'items' => ['required','array','min:1' , new CheckStockRule()],
            'items.*.product_id' => 'required|integer',
            'items.*.quantity' => 'required|integer|min:1',
        ];
    }

    public function messages()
    {
        return [
            'customer_id.exists' => 'The selected customer does not exist.',
            'payment_method.required' => 'The payment method is required.',
            'payment_method.in' => 'The payment method must be either cash or visa.',
            'items.required' => 'At least one order item is required.',
            'items.array' => 'The items must be an array.',
            'items.min' => 'At least one order item is required.',
            'items.*.product_id.required' => 'Each order item must have a product ID.',
            'items.*.product_id.exists' => 'The selected product does not exist.',
            'items.*.quantity.required' => 'Each order item must have a quantity.',
            'items.*.quantity.integer' => 'The quantity must be an integer.',
            'items.*.quantity.min' => 'The quantity must be at least 1.',
        ];
    }
}
