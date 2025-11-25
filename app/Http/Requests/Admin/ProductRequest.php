<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
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
     */
    public function rules(): array
    {
        $productId = $this->route('product')?->id;

        return [
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('products')->ignore($productId),
            ],
            'sku' => [
                'required',
                'string',
                'max:100',
                'regex:/^[A-Z0-9\-_]+$/',
                Rule::unique('products')->ignore($productId),
            ],
            'description' => ['nullable', 'string', 'max:5000'],
            'material' => ['nullable', 'string', 'max:255'],
            'brand' => ['nullable', 'string', 'max:255'],
            'price_pkr' => ['required', 'numeric', 'min:0', 'max:99999999'],
            'compare_at_price_pkr' => ['nullable', 'numeric', 'min:0', 'max:99999999', 'gt:price_pkr'],
            'stock' => ['required', 'integer', 'min:0', 'max:999999'],
            'is_active' => ['boolean'],
            'is_featured' => ['boolean'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * Get custom error messages.
     */
    public function messages(): array
    {
        return [
            'category_id.required' => 'Please select a category.',
            'category_id.exists' => 'The selected category does not exist.',
            'name.required' => 'Product name is required.',
            'name.max' => 'Product name cannot exceed 255 characters.',
            'slug.required' => 'Product slug is required.',
            'slug.regex' => 'Slug must contain only lowercase letters, numbers, and hyphens.',
            'slug.unique' => 'This slug is already taken.',
            'sku.required' => 'SKU is required.',
            'sku.regex' => 'SKU must contain only uppercase letters, numbers, hyphens, and underscores.',
            'sku.unique' => 'This SKU is already taken.',
            'description.max' => 'Description cannot exceed 5000 characters.',
            'price_pkr.required' => 'Price is required.',
            'price_pkr.numeric' => 'Price must be a valid number.',
            'price_pkr.min' => 'Price cannot be negative.',
            'price_pkr.max' => 'Price is too large.',
            'compare_at_price_pkr.gt' => 'Compare at price must be greater than the regular price.',
            'stock.required' => 'Stock quantity is required.',
            'stock.integer' => 'Stock must be a whole number.',
            'stock.min' => 'Stock cannot be negative.',
            'stock.max' => 'Stock quantity is too large.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Convert price from display format to storage format (paisa)
        if ($this->has('price_pkr')) {
            $this->merge([
                'price_pkr' => (int) ($this->input('price_pkr') * 100),
            ]);
        }

        if ($this->has('compare_at_price_pkr') && $this->input('compare_at_price_pkr')) {
            $this->merge([
                'compare_at_price_pkr' => (int) ($this->input('compare_at_price_pkr') * 100),
            ]);
        }

        // Generate slug from name if not provided
        if (!$this->has('slug') || !$this->input('slug')) {
            $this->merge([
                'slug' => \Str::slug($this->input('name')),
            ]);
        }
    }
}