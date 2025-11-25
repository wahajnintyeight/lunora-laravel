<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            // Customer Information
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'regex:/^(\+92|0)?[0-9]{10}$/', 'max:20'],
            
            // Shipping Address
            'shipping_first_name' => ['required', 'string', 'max:100'],
            'shipping_last_name' => ['required', 'string', 'max:100'],
            'shipping_address_line_1' => ['required', 'string', 'max:255'],
            'shipping_address_line_2' => ['nullable', 'string', 'max:255'],
            'shipping_city' => ['required', 'string', 'max:100'],
            'shipping_state' => ['required', 'string', 'max:100'],
            'shipping_postal_code' => ['required', 'string', 'regex:/^[0-9]{5}$/', 'max:10'],
            'shipping_country' => ['required', 'string', 'in:Pakistan'],
            
            // Billing Address (optional if same as shipping)
            'billing_same_as_shipping' => ['boolean'],
            'billing_first_name' => ['required_if:billing_same_as_shipping,false', 'nullable', 'string', 'max:100'],
            'billing_last_name' => ['required_if:billing_same_as_shipping,false', 'nullable', 'string', 'max:100'],
            'billing_address_line_1' => ['required_if:billing_same_as_shipping,false', 'nullable', 'string', 'max:255'],
            'billing_address_line_2' => ['nullable', 'string', 'max:255'],
            'billing_city' => ['required_if:billing_same_as_shipping,false', 'nullable', 'string', 'max:100'],
            'billing_state' => ['required_if:billing_same_as_shipping,false', 'nullable', 'string', 'max:100'],
            'billing_postal_code' => ['required_if:billing_same_as_shipping,false', 'nullable', 'string', 'regex:/^[0-9]{5}$/', 'max:10'],
            'billing_country' => ['required_if:billing_same_as_shipping,false', 'nullable', 'string', 'in:Pakistan'],
            
            // Order Notes
            'notes' => ['nullable', 'string', 'max:1000'],
            
            // Terms and Conditions
            'terms_accepted' => ['required', 'accepted'],
            
            // Coupon Code
            'coupon_code' => ['nullable', 'string', 'max:50', 'regex:/^[A-Z0-9\-_]+$/'],
        ];
    }

    /**
     * Get custom error messages.
     */
    public function messages(): array
    {
        return [
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'phone.required' => 'Phone number is required.',
            'phone.regex' => 'Please enter a valid Pakistani phone number.',
            
            'shipping_first_name.required' => 'First name is required.',
            'shipping_last_name.required' => 'Last name is required.',
            'shipping_address_line_1.required' => 'Address is required.',
            'shipping_city.required' => 'City is required.',
            'shipping_state.required' => 'State/Province is required.',
            'shipping_postal_code.required' => 'Postal code is required.',
            'shipping_postal_code.regex' => 'Postal code must be 5 digits.',
            'shipping_country.required' => 'Country is required.',
            'shipping_country.in' => 'We currently only ship within Pakistan.',
            
            'billing_first_name.required_if' => 'Billing first name is required.',
            'billing_last_name.required_if' => 'Billing last name is required.',
            'billing_address_line_1.required_if' => 'Billing address is required.',
            'billing_city.required_if' => 'Billing city is required.',
            'billing_state.required_if' => 'Billing state/province is required.',
            'billing_postal_code.required_if' => 'Billing postal code is required.',
            'billing_postal_code.regex' => 'Billing postal code must be 5 digits.',
            'billing_country.required_if' => 'Billing country is required.',
            'billing_country.in' => 'Billing address must be within Pakistan.',
            
            'notes.max' => 'Order notes cannot exceed 1000 characters.',
            'terms_accepted.required' => 'You must accept the terms and conditions.',
            'terms_accepted.accepted' => 'You must accept the terms and conditions.',
            
            'coupon_code.regex' => 'Invalid coupon code format.',
        ];
    }

    /**
     * Get custom attribute names.
     */
    public function attributes(): array
    {
        return [
            'shipping_first_name' => 'first name',
            'shipping_last_name' => 'last name',
            'shipping_address_line_1' => 'address',
            'shipping_address_line_2' => 'address line 2',
            'shipping_city' => 'city',
            'shipping_state' => 'state/province',
            'shipping_postal_code' => 'postal code',
            'shipping_country' => 'country',
            
            'billing_first_name' => 'billing first name',
            'billing_last_name' => 'billing last name',
            'billing_address_line_1' => 'billing address',
            'billing_address_line_2' => 'billing address line 2',
            'billing_city' => 'billing city',
            'billing_state' => 'billing state/province',
            'billing_postal_code' => 'billing postal code',
            'billing_country' => 'billing country',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Clean phone number
        if ($this->has('phone')) {
            $phone = preg_replace('/[^0-9+]/', '', $this->input('phone'));
            $this->merge(['phone' => $phone]);
        }

        // Clean postal codes
        if ($this->has('shipping_postal_code')) {
            $postalCode = preg_replace('/[^0-9]/', '', $this->input('shipping_postal_code'));
            $this->merge(['shipping_postal_code' => $postalCode]);
        }

        if ($this->has('billing_postal_code')) {
            $postalCode = preg_replace('/[^0-9]/', '', $this->input('billing_postal_code'));
            $this->merge(['billing_postal_code' => $postalCode]);
        }

        // Clean coupon code
        if ($this->has('coupon_code') && $this->input('coupon_code')) {
            $couponCode = strtoupper(trim($this->input('coupon_code')));
            $this->merge(['coupon_code' => $couponCode]);
        }
    }
}