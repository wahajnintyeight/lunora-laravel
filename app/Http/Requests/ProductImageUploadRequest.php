<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductImageUploadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'images' => 'required|array|max:10',
            'images.*' => [
                'required',
                'image',
                'mimes:jpeg,jpg,png,webp',
                'max:2048', // 2MB
                'dimensions:min_width=300,min_height=300,max_width=2000,max_height=2000'
            ],
            'alt_texts' => 'nullable|array',
            'alt_texts.*' => 'nullable|string|max:255',
            'product_id' => 'required|exists:products,id',
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'images.required' => 'Please select at least one image to upload.',
            'images.max' => 'You can upload a maximum of 10 images at once.',
            'images.*.required' => 'Each selected file must be a valid image.',
            'images.*.image' => 'Each file must be a valid image.',
            'images.*.mimes' => 'Images must be in JPEG, PNG, or WebP format.',
            'images.*.max' => 'Each image must not exceed 2MB in size.',
            'images.*.dimensions' => 'Images must be between 300x300 and 2000x2000 pixels.',
            'product_id.required' => 'Product ID is required.',
            'product_id.exists' => 'The selected product does not exist.',
            'alt_texts.*.max' => 'Alt text must not exceed 255 characters.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'images.*' => 'image file',
            'alt_texts.*' => 'alt text',
        ];
    }
}