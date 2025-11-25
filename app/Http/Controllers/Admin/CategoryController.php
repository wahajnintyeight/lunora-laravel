<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategoryController extends AdminController
{
    public function index(Request $request)
    {
        $view = $request->get('view', 'list'); // 'list' or 'tree'

        if ($view === 'tree') {
            $categories = Category::with(['children.children', 'parent'])
                ->withCount('products')
                ->whereNull('parent_id')
                ->orderBy('name')
                ->get();

            return view('admin.categories.tree', compact('categories'));
        }

        // List view with search and filters
        $query = Category::with('parent')->withCount('products');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        if ($request->filled('parent')) {
            if ($request->parent === 'root') {
                $query->whereNull('parent_id');
            } else {
                $query->where('parent_id', $request->parent);
            }
        }

        $categories = $query->orderBy('name')->paginate(20);
        $parentCategories = Category::whereNull('parent_id')->orderBy('name')->get();

        return view('admin.categories.index', compact('categories', 'parentCategories'));
    }

    public function create()
    {
        $parentCategories = Category::whereNull('parent_id')
            ->orderBy('name')
            ->get();

        return view('admin.categories.create', compact('parentCategories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $category = Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'parent_id' => $request->parent_id,
            'description' => $request->description,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function show(Category $category)
    {
        $category->load(['parent', 'children', 'products']);
        
        return view('admin.categories.show', compact('category'));
    }

    public function edit(Category $category)
    {
        $parentCategories = Category::whereNull('parent_id')
            ->where('id', '!=', $category->id)
            ->orderBy('name')
            ->get();

        return view('admin.categories.edit', compact('category', 'parentCategories'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'parent_id' => $request->parent_id,
            'description' => $request->description,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        if ($category->products()->count() > 0) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Cannot delete category with products.');
        }

        if ($category->children()->count() > 0) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Cannot delete category with subcategories.');
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category deleted successfully.');
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'categories' => 'required|array|min:1',
            'categories.*' => 'exists:categories,id',
        ]);

        $categoryIds = $request->categories;
        $action = $request->action;

        DB::beginTransaction();
        try {
            switch ($action) {
                case 'activate':
                    Category::whereIn('id', $categoryIds)->update(['is_active' => true]);
                    $message = 'Categories activated successfully.';
                    break;

                case 'deactivate':
                    Category::whereIn('id', $categoryIds)->update(['is_active' => false]);
                    $message = 'Categories deactivated successfully.';
                    break;

                case 'delete':
                    $categories = Category::whereIn('id', $categoryIds)->get();
                    foreach ($categories as $category) {
                        if ($category->products()->count() > 0) {
                            throw new \Exception("Cannot delete category '{$category->name}' with products.");
                        }
                        if ($category->children()->count() > 0) {
                            throw new \Exception("Cannot delete category '{$category->name}' with subcategories.");
                        }
                        $category->delete();
                    }
                    $message = 'Categories deleted successfully.';
                    break;
            }

            DB::commit();

            return redirect()->route('admin.categories.index')
                ->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'categories' => 'required|array',
            'categories.*.id' => 'required|exists:categories,id',
            'categories.*.parent_id' => 'nullable|exists:categories,id',
            'categories.*.sort_order' => 'required|integer|min:0',
        ]);

        DB::beginTransaction();
        try {
            foreach ($request->categories as $categoryData) {
                Category::where('id', $categoryData['id'])->update([
                    'parent_id' => $categoryData['parent_id'],
                    'sort_order' => $categoryData['sort_order'],
                ]);
            }

            DB::commit();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}