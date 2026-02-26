<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
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
            'name' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:20|unique:customers,phone,' . $this->customer->id,
            'address' => 'sometimes|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'name.string' => 'The customer name must be a string.',
            'name.max' => 'The customer name may not be greater than 255 characters.',
            'phone.string' => 'The phone number must be a string.',
            'phone.max' => 'The phone number may not be greater than 20 characters.',
            'phone.unique' => 'This phone number is already registered.',
            'address.string' => 'The address must be a string.',
            'address.max' => 'The address may not be greater than 500 characters.',
        ];
    }
}
