<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Media;

class HomeController extends Controller
{
    /**
     * Show home page with categories
     */
    public function index()
    {
        $categories = Category::withCount('media')->get();
        $recentMedia = Media::orderByDesc('created_at')->limit(12)->get();
        
        return view('home', compact('categories', 'recentMedia'));
    }

    /**
     * Browse media by category
     */
    public function category(Category $category)
    {
        $media = $category->media()->paginate(12);
        return view('category', compact('category', 'media'));
    }

    /**
     * Show media details
     */
    public function show(Media $medium)
    {
        return view('media.show', compact('medium'));
    }

    /**
     * Search media
     */
    public function search()
    {
        $query = request('q');
        
        if (strlen($query) < 2) {
            return view('search', ['media' => collect(), 'query' => $query]);
        }

        $media = Media::where('title', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->paginate(12)
            ->appends(['q' => $query]);

        return view('search', compact('media', 'query'));
    }
}
