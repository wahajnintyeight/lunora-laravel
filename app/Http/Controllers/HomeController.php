<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Display the home page with featured products and categories.
     */
    public function index(): View
    {
        // Get featured products with their images and category
        $featuredProducts = Product::with(['images', 'category'])
            ->where('is_active', true)
            ->where('is_featured', true)
            ->where('stock', '>', 0)
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        // Get main categories (parent categories only) for navigation
        $mainCategories = Category::whereNull('parent_id')
            ->where('is_active', true)
            ->whereHas('products', function ($query) {
                $query->where('is_active', true)->where('stock', '>', 0);
            })
            ->withCount(['products' => function ($query) {
                $query->where('is_active', true)->where('stock', '>', 0);
            }])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        // Get latest products for "New Arrivals" section
        $newArrivals = Product::with(['images', 'category'])
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        // Get categories with their featured products for showcase
        $categoryShowcase = Category::with(['products' => function ($query) {
                $query->with('images')
                    ->where('is_active', true)
                    ->where('stock', '>', 0)
                    ->orderBy('is_featured', 'desc')
                    ->orderBy('created_at', 'desc')
                    ->limit(4);
            }])
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->whereHas('products', function ($query) {
                $query->where('is_active', true)->where('stock', '>', 0);
            })
            ->orderBy('sort_order')
            ->limit(3)
            ->get();

        return view('home', compact(
            'featuredProducts',
            'mainCategories',
            'newArrivals',
            'categoryShowcase'
        ));
    }
}