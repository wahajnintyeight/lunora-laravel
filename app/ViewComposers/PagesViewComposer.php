<?php

namespace App\ViewComposers;

use App\Models\Page;
use Illuminate\View\View;
use Illuminate\Support\Facades\Cache;

class PagesViewComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        // Cache published pages for 1 hour
        $pages = Cache::remember('published_pages', 3600, function () {
            return Page::published()
                ->orderBy('sort_order')
                ->orderBy('title')
                ->get()
                ->keyBy('slug');
        });

        // Helper function to get page by slug
        $getPage = function ($slug) use ($pages) {
            return $pages->get($slug);
        };

        // Helper function to check if page exists
        $hasPage = function ($slug) use ($pages) {
            return $pages->has($slug);
        };

        $view->with('publishedPages', $pages);
        $view->with('getPage', $getPage);
        $view->with('hasPage', $hasPage);
    }
}

