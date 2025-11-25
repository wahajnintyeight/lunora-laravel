<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductOption;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends AdminController
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'images'])
            ->withCount('variants');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $products = $query->latest()->paginate(20);
        $categories = Category::orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:100|unique:products',
            'description' => 'nullable|string',
            'material' => 'nullable|string|max:255',
            'brand' => 'nullable|string|max:255',
            'price_pkr' => 'required|numeric|min:0',
            'compare_at_price_pkr' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'images.*' => 'image|mimes:jpeg,png,webp|max:2048',
            'image_urls.*' => 'nullable|url|max:2048',
        ]);

        DB::beginTransaction();
        try {
            // Convert price to paisa
            $validated['price_pkr'] = (int) ($validated['price_pkr'] * 100);
            if ($validated['compare_at_price_pkr']) {
                $validated['compare_at_price_pkr'] = (int) ($validated['compare_at_price_pkr'] * 100);
            }

            // Generate slug
            $validated['slug'] = Str::slug($validated['name']);

            $product = Product::create($validated);

            // Handle image uploads
            if ($request->hasFile('images')) {
                $this->uploadProductImages($product, $request->file('images'));
            }

            // Handle image URLs
            if ($request->filled('image_urls')) {
                $this->saveProductImageUrls($product, array_filter($request->image_urls));
            }

            DB::commit();

            return redirect()->route('admin.products.index')
                ->with('success', 'Product created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Failed to create product: ' . $e->getMessage());
        }
    }

    public function show(Product $product)
    {
        $product->load(['category', 'images', 'variants']);
        
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $product->load(['images', 'variants']);
        $categories = Category::orderBy('name')->get();
        
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:100|unique:products,sku,' . $product->id,
            'description' => 'nullable|string',
            'material' => 'nullable|string|max:255',
            'brand' => 'nullable|string|max:255',
            'price_pkr' => 'required|numeric|min:0',
            'compare_at_price_pkr' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'images.*' => 'image|mimes:jpeg,png,webp|max:2048',
            'image_urls.*' => 'nullable|url|max:2048',
        ]);

        DB::beginTransaction();
        try {
            // Convert price to paisa
            $validated['price_pkr'] = (int) ($validated['price_pkr'] * 100);
            if ($validated['compare_at_price_pkr']) {
                $validated['compare_at_price_pkr'] = (int) ($validated['compare_at_price_pkr'] * 100);
            }

            // Update slug if name changed
            if ($product->name !== $validated['name']) {
                $validated['slug'] = Str::slug($validated['name']);
            }

            $product->update($validated);

            // Handle new image uploads
            if ($request->hasFile('images')) {
                $this->uploadProductImages($product, $request->file('images'));
            }

            // Handle image URLs
            if ($request->filled('image_urls')) {
                $this->saveProductImageUrls($product, array_filter($request->image_urls));
            }

            DB::commit();

            return redirect()->route('admin.products.index')
                ->with('success', 'Product updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Failed to update product: ' . $e->getMessage());
        }
    }

    public function destroy(Product $product)
    {
        DB::beginTransaction();
        try {
            // Delete product images
            foreach ($product->images as $image) {
                // Image file cleanup is handled by ProductImage model events
                $image->delete();
            }

            // Delete variants
            $product->variants()->delete();

            // Delete options and their values
            foreach ($product->options as $option) {
                $option->values()->delete();
                $option->delete();
            }

            // Delete the product
            $product->delete();

            DB::commit();

            return redirect()->route('admin.products.index')
                ->with('success', 'Product deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete product: ' . $e->getMessage());
        }
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'products' => 'required|array|min:1',
            'products.*' => 'exists:products,id',
        ]);

        $productIds = $request->products;
        $action = $request->action;

        DB::beginTransaction();
        try {
            switch ($action) {
                case 'activate':
                    Product::whereIn('id', $productIds)->update(['is_active' => true]);
                    $message = 'Products activated successfully.';
                    break;

                case 'deactivate':
                    Product::whereIn('id', $productIds)->update(['is_active' => false]);
                    $message = 'Products deactivated successfully.';
                    break;

                case 'delete':
                    $products = Product::whereIn('id', $productIds)->get();
                    foreach ($products as $product) {
                        // Delete product images
                        foreach ($product->images as $image) {
                            // Image file cleanup is handled by ProductImage model events
                            $image->delete();
                        }

                        // Delete variants
                        $product->variants()->delete();

                        // Delete options and their values
                        foreach ($product->options as $option) {
                            $option->values()->delete();
                            $option->delete();
                        }

                        $product->delete();
                    }
                    $message = 'Products deleted successfully.';
                    break;
            }

            DB::commit();

            return redirect()->route('admin.products.index')
                ->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to perform bulk action: ' . $e->getMessage());
        }
    }

    public function deleteImage(ProductImage $image)
    {
        try {
            // Image file cleanup is handled by ProductImage model events
            $image->delete();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete image: ' . $e->getMessage(),
            ], 422);
        }
    }

    public function reorderImages(Request $request, Product $product)
    {
        $request->validate([
            'images' => 'required|array',
            'images.*' => 'exists:product_images,id',
        ]);

        DB::beginTransaction();
        try {
            foreach ($request->images as $index => $imageId) {
                ProductImage::where('id', $imageId)
                    ->where('product_id', $product->id)
                    ->update(['sort_order' => $index + 1]);
            }

            DB::commit();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to reorder images: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function variants(Product $product)
    {
        $product->load(['variants', 'options.values']);
        
        return view('admin.products.variants', compact('product'));
    }

    public function storeVariant(Request $request, Product $product)
    {
        $request->validate([
            'sku' => 'required|string|max:100|unique:product_variants',
            'price_pkr' => 'nullable|numeric|min:0',
            'compare_at_price_pkr' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'weight' => 'nullable|numeric|min:0',
            'options' => 'required|array',
            'is_active' => 'boolean',
        ]);

        DB::beginTransaction();
        try {
            $variantData = [
                'product_id' => $product->id,
                'sku' => $request->sku,
                'stock' => $request->stock,
                'weight' => $request->weight,
                'options_json' => $request->options,
                'is_active' => $request->boolean('is_active', true),
            ];

            // Convert prices to paisa if provided
            if ($request->filled('price_pkr')) {
                $variantData['price_pkr'] = (int) ($request->price_pkr * 100);
            }
            if ($request->filled('compare_at_price_pkr')) {
                $variantData['compare_at_price_pkr'] = (int) ($request->compare_at_price_pkr * 100);
            }

            $product->variants()->create($variantData);

            DB::commit();

            return redirect()->route('admin.products.variants', $product)
                ->with('success', 'Variant created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Failed to create variant: ' . $e->getMessage());
        }
    }

    public function updateVariant(Request $request, Product $product, ProductVariant $variant)
    {
        $request->validate([
            'sku' => 'required|string|max:100|unique:product_variants,sku,' . $variant->id,
            'price_pkr' => 'nullable|numeric|min:0',
            'compare_at_price_pkr' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'weight' => 'nullable|numeric|min:0',
            'options' => 'required|array',
            'is_active' => 'boolean',
        ]);

        DB::beginTransaction();
        try {
            $variantData = [
                'sku' => $request->sku,
                'stock' => $request->stock,
                'weight' => $request->weight,
                'options_json' => $request->options,
                'is_active' => $request->boolean('is_active', true),
            ];

            // Convert prices to paisa if provided
            if ($request->filled('price_pkr')) {
                $variantData['price_pkr'] = (int) ($request->price_pkr * 100);
            } else {
                $variantData['price_pkr'] = null;
            }

            if ($request->filled('compare_at_price_pkr')) {
                $variantData['compare_at_price_pkr'] = (int) ($request->compare_at_price_pkr * 100);
            } else {
                $variantData['compare_at_price_pkr'] = null;
            }

            $variant->update($variantData);

            DB::commit();

            return redirect()->route('admin.products.variants', $product)
                ->with('success', 'Variant updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Failed to update variant: ' . $e->getMessage());
        }
    }

    public function destroyVariant(Product $product, ProductVariant $variant)
    {
        try {
            $variant->delete();

            return redirect()->route('admin.products.variants', $product)
                ->with('success', 'Variant deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete variant: ' . $e->getMessage());
        }
    }

    public function options(Product $product)
    {
        $product->load(['options.values']);
        
        return view('admin.products.options', compact('product'));
    }

    public function storeOption(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:select,radio,checkbox,text,textarea',
            'is_required' => 'boolean',
            'values' => 'required_unless:type,text,textarea|array|min:1',
            'values.*.value' => 'required|string|max:255',
            'values.*.price_adjustment_pkr' => 'nullable|numeric',
            'values.*.is_default' => 'boolean',
        ]);

        DB::beginTransaction();
        try {
            $option = $product->options()->create([
                'name' => $request->name,
                'type' => $request->type,
                'is_required' => $request->boolean('is_required'),
                'sort_order' => $product->options()->count() + 1,
            ]);

            if (in_array($request->type, ['select', 'radio', 'checkbox']) && $request->values) {
                foreach ($request->values as $index => $valueData) {
                    $option->values()->create([
                        'value' => $valueData['value'],
                        'price_adjustment_pkr' => isset($valueData['price_adjustment_pkr']) 
                            ? (int) ($valueData['price_adjustment_pkr'] * 100) 
                            : 0,
                        'sort_order' => $index + 1,
                        'is_default' => $valueData['is_default'] ?? false,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('admin.products.options', $product)
                ->with('success', 'Option created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Failed to create option: ' . $e->getMessage());
        }
    }

    public function destroyOption(Product $product, ProductOption $option)
    {
        DB::beginTransaction();
        try {
            $option->values()->delete();
            $option->delete();

            DB::commit();

            return redirect()->route('admin.products.options', $product)
                ->with('success', 'Option deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete option: ' . $e->getMessage());
        }
    }

    private function uploadProductImages(Product $product, array $images)
    {
        foreach ($images as $index => $image) {
            $filename = 'product-' . $product->id . '-' . time() . '-' . ($index + 1) . '.' . $image->getClientOriginalExtension();
            $path = 'products/' . $filename;

            // Store original image
            $image->storeAs('products', $filename, 'public');

            // Save to database (legacy path stored in file_path)
            ProductImage::create([
                'product_id' => $product->id,
                'file_path' => $path,
                'alt_text' => $product->name,
                'sort_order' => $product->images()->count() + $index + 1,
            ]);
        }
    }

    private function saveProductImageUrls(Product $product, array $imageUrls)
    {
        foreach ($imageUrls as $index => $url) {
            // Validate URL is accessible and is an image
            if ($this->validateImageUrl($url)) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'file_path' => $url, // Store URL directly in file_path
                    'alt_text' => $product->name,
                    'sort_order' => $product->images()->count() + $index + 1,
                ]);
            }
        }
    }

    private function validateImageUrl($url)
    {
        try {
            // Check if URL is valid
            if (!filter_var($url, FILTER_VALIDATE_URL)) {
                return false;
            }

            // Check if URL points to an image by checking headers
            $headers = @get_headers($url, 1);
            if (!$headers) {
                return false;
            }

            // Check if the response is successful
            if (strpos($headers[0], '200') === false) {
                return false;
            }

            // Check content type
            $contentType = isset($headers['Content-Type']) ? $headers['Content-Type'] : '';
            if (is_array($contentType)) {
                $contentType = $contentType[0];
            }

            return strpos($contentType, 'image/') === 0;
        } catch (\Exception $e) {
            return false;
        }
    }
}