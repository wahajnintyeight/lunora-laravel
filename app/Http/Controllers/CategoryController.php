<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Display products for a specific category.
     */
    public function show(Category $category, Request $request): View
    {
        // Check if category is active
        if (!$category->is_active) {
            abort(404);
        }

        // Load category relationships
        $category->load(['children' => function ($query) {
            $query->where('is_active', true)
                ->withCount(['products' => function ($q) {
                    $q->where('is_active', true)->where('stock', '>', 0);
                }])
                ->having('products_count', '>', 0)
                ->orderBy('sort_order')
                ->orderBy('name');
        }]);

        // Get all category IDs including subcategories
        $categoryIds = $category->descendants()->pluck('id')->push($category->id);

        // Build products query
        $query = Product::with(['images', 'category', 'variants'])
            ->whereIn('category_id', $categoryIds)
            ->where('is_active', true)
            ->where('stock', '>', 0);

        // Apply search filter within category
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%")
                  ->orWhere('sku', 'like', "%{$searchTerm}%")
                  ->orWhere('material', 'like', "%{$searchTerm}%")
                  ->orWhere('brand', 'like', "%{$searchTerm}%");
            });
        }

        // Apply subcategory filter
        if ($request->filled('subcategory')) {
            $subcategory = Category::where('slug', $request->subcategory)
                ->where('parent_id', $category->id)
                ->first();
            if ($subcategory) {
                $subcategoryIds = $subcategory->descendants()->pluck('id')->push($subcategory->id);
                $query->whereIn('category_id', $subcategoryIds);
            }
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

        // Get filter options specific to this category
        $materials = Product::whereIn('category_id', $categoryIds)
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->whereNotNull('material')
            ->distinct()
            ->pluck('material')
            ->sort()
            ->values();

        $brands = Product::whereIn('category_id', $categoryIds)
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->whereNotNull('brand')
            ->distinct()
            ->pluck('brand')
            ->sort()
            ->values();

        // Get price range for this category
        $priceRange = Product::whereIn('category_id', $categoryIds)
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->selectRaw('MIN(price_pkr) as min_price, MAX(price_pkr) as max_price')
            ->first();

        // Get breadcrumb trail
        $breadcrumbs = $this->getBreadcrumbs($category);

        // Get featured products from this category for hero section
        $featuredProducts = Product::with(['images'])
            ->whereIn('category_id', $categoryIds)
            ->where('is_active', true)
            ->where('is_featured', true)
            ->where('stock', '>', 0)
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        return view('categories.show', compact(
            'category',
            'products',
            'materials',
            'brands',
            'priceRange',
            'breadcrumbs',
            'featuredProducts'
        ));
    }

    /**
     * Get all categories for navigation (API endpoint).
     */
    public function index(Request $request)
    {
        $categories = Category::with(['children' => function ($query) {
                $query->where('is_active', true)
                    ->withCount(['products' => function ($q) {
                        $q->where('is_active', true)->where('stock', '>', 0);
                    }])
                    ->having('products_count', '>', 0)
                    ->orderBy('sort_order')
                    ->orderBy('name');
            }])
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->withCount(['products' => function ($query) {
                $query->where('is_active', true)->where('stock', '>', 0);
            }])
            ->having('products_count', '>', 0)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        if ($request->expectsJson()) {
            return response()->json([
                'categories' => $categories->map(function ($category) {
                    return [
                        'id' => $category->id,
                        'name' => $category->name,
                        'slug' => $category->slug,
                        'products_count' => $category->products_count,
                        'children' => $category->children->map(function ($child) {
                            return [
                                'id' => $child->id,
                                'name' => $child->name,
                                'slug' => $child->slug,
                                'products_count' => $child->products_count,
                            ];
                        })
                    ];
                })
            ]);
        }

        return view('categories.index', compact('categories'));
    }

    /**
     * Get category suggestions for search autocomplete.
     */
    public function suggestions(Request $request)
    {
        // If no query, return all categories for catalog dropdown
        $query = $request->get('q', '');
        
        if (empty($query)) {
            return $this->getCatalogCategories();
        }
        
        if (strlen($query) < 2) {
            return response()->json(['suggestions' => []]);
        }

        $categories = Category::where('is_active', true)
            ->where('name', 'like', "%{$query}%")
            ->withCount(['products' => function ($q) {
                $q->where('is_active', true)->where('stock', '>', 0);
            }])
            ->having('products_count', '>', 0)
            ->orderBy('name')
            ->limit(10)
            ->get(['id', 'name', 'slug']);

        return response()->json([
            'suggestions' => $categories->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'url' => route('category.show', $category->slug),
                ];
            })
        ]);
    }

    /**
     * Get all categories with hierarchy for catalog dropdown.
     */
    protected function getCatalogCategories()
    {
        $categories = Category::with(['children' => function ($query) {
                $query->where('is_active', true)
                    ->with(['children' => function ($q) {
                        $q->where('is_active', true)
                            ->withCount(['products' => function ($pq) {
                                $pq->where('is_active', true)->where('stock', '>', 0);
                            }])
                            ->orderBy('sort_order')
                            ->orderBy('name');
                    }])
                    ->withCount(['products' => function ($q) {
                        $q->where('is_active', true)->where('stock', '>', 0);
                    }])
                    ->orderBy('sort_order')
                    ->orderBy('name');
            }])
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->withCount(['products' => function ($query) {
                $query->where('is_active', true)->where('stock', '>', 0);
            }])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return response()->json([
            'categories' => $categories->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'url' => route('category.show', $category->slug),
                    'products_count' => $category->products_count,
                    'children' => $category->children->map(function ($child) {
                        return [
                            'id' => $child->id,
                            'name' => $child->name,
                            'slug' => $child->slug,
                            'url' => route('category.show', $child->slug),
                            'products_count' => $child->products_count,
                            'children' => $child->children->map(function ($grandchild) {
                                return [
                                    'id' => $grandchild->id,
                                    'name' => $grandchild->name,
                                    'slug' => $grandchild->slug,
                                    'url' => route('category.show', $grandchild->slug),
                                    'products_count' => $grandchild->products_count,
                                ];
                            })
                        ];
                    })
                ];
            })
        ]);
    }

    /**
     * Get breadcrumb trail for a category.
     */
    private function getBreadcrumbs(Category $category): array
    {
        $breadcrumbs = [];
        $current = $category;

        // Build breadcrumbs from current category up to root
        while ($current) {
            array_unshift($breadcrumbs, [
                'name' => $current->name,
                'slug' => $current->slug,
                'url' => route('category.show', $current->slug),
                'is_current' => $current->id === $category->id
            ]);
            $current = $current->parent;
        }

        // Add home breadcrumb
        array_unshift($breadcrumbs, [
            'name' => 'Home',
            'slug' => '',
            'url' => route('home'),
            'is_current' => false
        ]);

        return $breadcrumbs;
    }
}