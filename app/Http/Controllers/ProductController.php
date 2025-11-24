<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductOption;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Database\Eloquent\Builder;

class ProductController extends Controller
{
    /**
     * Display a listing of products with filtering and search.
     */
    public function index(Request $request): View
    {
        $query = Product::with(['images', 'category', 'variants'])
            ->where('is_active', true)
            ->where('stock', '>', 0);

        // Apply category filter
        if ($request->filled('category')) {
            $category = Category::where('slug', $request->category)->first();
            if ($category) {
                // Include products from this category and its subcategories
                $categoryIds = $category->descendants()->pluck('id')->push($category->id);
                $query->whereIn('category_id', $categoryIds);
            }
        }

        // Apply search filter
        if ($request->filled('search')) {
            $searchTerm = strtolower($request->search);
            $query->where(function (Builder $q) use ($searchTerm) {
                $q->whereRaw('LOWER(name) LIKE ?', ["%{$searchTerm}%"])
                  ->orWhereRaw('LOWER(description) LIKE ?', ["%{$searchTerm}%"])
                  ->orWhereRaw('LOWER(sku) LIKE ?', ["%{$searchTerm}%"])
                  ->orWhereRaw('LOWER(material) LIKE ?', ["%{$searchTerm}%"])
                  ->orWhereRaw('LOWER(brand) LIKE ?', ["%{$searchTerm}%"]);
            });
        }

        // Apply price range filter
        if ($request->filled('min_price')) {
            $minPrice = (int) ($request->min_price * 100); // Convert to paisa
            $query->where('price_pkr', '>=', $minPrice);
        }

        if ($request->filled('max_price')) {
            $maxPrice = (int) ($request->max_price * 100); // Convert to paisa
            $query->where('price_pkr', '<=', $maxPrice);
        }

        // Apply material filter
        if ($request->filled('material')) {
            $query->where('material', $request->material);
        }

        // Apply brand filter
        if ($request->filled('brand')) {
            $query->where('brand', $request->brand);
        }

        // Apply sorting
        $sortBy = $request->get('sort', 'name');
        $sortDirection = $request->get('direction', 'asc');

        switch ($sortBy) {
            case 'price':
                $query->orderBy('price_pkr', $sortDirection);
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'featured':
                $query->orderBy('is_featured', 'desc')->orderBy('name', 'asc');
                break;
            case 'name':
            default:
                $query->orderBy('name', $sortDirection);
                break;
        }

        // Paginate results
        $products = $query->paginate(12)->withQueryString();

        // Get filter options for sidebar
        $categories = Category::whereNull('parent_id')
            ->where('is_active', true)
            ->whereHas('products', function ($query) {
                $query->where('is_active', true)->where('stock', '>', 0);
            })
            ->withCount(['products' => function ($query) {
                $query->where('is_active', true)->where('stock', '>', 0);
            }])
            ->orderBy('name')
            ->get();

        $materials = Product::where('is_active', true)
            ->where('stock', '>', 0)
            ->whereNotNull('material')
            ->distinct()
            ->pluck('material')
            ->sort()
            ->values();

        $brands = Product::where('is_active', true)
            ->where('stock', '>', 0)
            ->whereNotNull('brand')
            ->distinct()
            ->pluck('brand')
            ->sort()
            ->values();

        // Get price range for filter
        $priceRange = Product::where('is_active', true)
            ->where('stock', '>', 0)
            ->selectRaw('MIN(price_pkr) as min_price, MAX(price_pkr) as max_price')
            ->first();

        return view('products.index', compact(
            'products',
            'categories',
            'materials',
            'brands',
            'priceRange'
        ));
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product): View
    {
        // Check if product is active and in stock
        if (!$product->is_active || $product->stock <= 0) {
            abort(404);
        }

        // Load relationships
        $product->load([
            'images' => function ($query) {
                $query->orderBy('sort_order')->orderBy('id');
            },
            'category',
            'options.values',
            'variants' => function ($query) {
                $query->where('is_active', true)->where('stock', '>', 0);
            }
        ]);

        // Get related products from the same category
        $relatedProducts = Product::with(['images', 'category'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->orderBy('is_featured', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get();

        // Get product options grouped for variant selection
        $productOptions = $product->options->groupBy('name');

        // Check if product has variants
        $hasVariants = $product->variants->count() > 0;

        // Get available stock (total or variant-based)
        $availableStock = $hasVariants 
            ? $product->variants->sum('stock')
            : $product->stock;

        return view('products.show', compact(
            'product',
            'relatedProducts',
            'productOptions',
            'hasVariants',
            'availableStock'
        ));
    }

    /**
     * Search products and return results.
     */
    public function search(Request $request)
    {
        $searchTerm = $request->get('q', '');
        
        if (empty($searchTerm)) {
            return redirect()->route('products.index');
        }

        $query = Product::with(['images', 'category'])
            ->where('is_active', true)
            ->where('stock', '>', 0);

        // Search in multiple fields
        $searchTermLower = strtolower($searchTerm);
        $query->where(function (Builder $q) use ($searchTermLower) {
            $q->whereRaw('LOWER(name) LIKE ?', ["%{$searchTermLower}%"])
              ->orWhereRaw('LOWER(description) LIKE ?', ["%{$searchTermLower}%"])
              ->orWhereRaw('LOWER(sku) LIKE ?', ["%{$searchTermLower}%"])
              ->orWhereRaw('LOWER(material) LIKE ?', ["%{$searchTermLower}%"])
              ->orWhereRaw('LOWER(brand) LIKE ?', ["%{$searchTermLower}%"]);
        });

        // Order by relevance (exact matches first, then partial matches)
        $query->orderByRaw("
            CASE 
                WHEN name = ? THEN 1
                WHEN name LIKE ? THEN 2
                WHEN description LIKE ? THEN 3
                WHEN sku LIKE ? THEN 4
                ELSE 5
            END
        ", [$searchTerm, "{$searchTerm}%", "%{$searchTerm}%", "%{$searchTerm}%"]);

        $products = $query->paginate(12)->withQueryString();

        // Get search suggestions if no results found
        $suggestions = [];
        if ($products->count() === 0) {
            // Get some popular product names as suggestions
            $suggestions = Product::where('is_active', true)
                ->where('stock', '>', 0)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->pluck('name')
                ->toArray();
        }

        return view('products.search', compact(
            'products',
            'searchTerm',
            'suggestions'
        ));
    }

    /**
     * Get product variants based on selected options (AJAX endpoint).
     */
    public function getVariants(Request $request, Product $product)
    {
        $selectedOptions = $request->get('options', []);
        
        $variants = $product->variants()
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->get()
            ->filter(function ($variant) use ($selectedOptions) {
                $variantOptions = $variant->options_json ?? [];
                
                foreach ($selectedOptions as $optionName => $optionValue) {
                    if (!isset($variantOptions[$optionName]) || 
                        $variantOptions[$optionName] !== $optionValue) {
                        return false;
                    }
                }
                
                return true;
            });

        return response()->json([
            'variants' => $variants->map(function ($variant) {
                return [
                    'id' => $variant->id,
                    'sku' => $variant->sku,
                    'price_pkr' => $variant->price_pkr,
                    'formatted_price' => 'PKR ' . number_format(($variant->price_pkr ?? $variant->product->price_pkr) / 100, 2),
                    'stock' => $variant->stock,
                    'options' => $variant->options_json,
                ];
            })->values()
        ]);
    }
}