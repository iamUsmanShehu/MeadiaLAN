<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Jobs\ProcessMediaVersions;

class MediaController extends Controller
{
    /**
     * Show all media
     */
    public function index()
    {
        $media = Media::with('category')
            ->orderBy('is_processed', 'asc') // Unprocessed first
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        $pendingCount = Media::where('is_processed', false)->count();
        
        return view('admin.media.index', compact('media', 'pendingCount'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.media.create', compact('categories'));
    }

    /**
     * Store media
     */
    public function store(Request $request)
    {
        // Increase execution time for large file uploads (10 minutes)
        set_time_limit(600);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'type' => 'required|in:movie,series',
            'category_id' => 'required|exists:categories,id',
            'file' => 'required|file|max:2097152', // 2GB max (realistic for web)
            'poster' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB for poster
            'duration' => 'nullable|integer|min:1',
            'year' => 'nullable|string|max:4',
            'director' => 'nullable|string|max:255',
            'cast' => 'nullable|string|max:500',
            'episodes' => 'nullable|integer|min:1',
        ]);

        try {
            // Store media file
            $file = $request->file('file');
            $filename = time() . '_' . Str::slug($request->title) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('media', $filename, 'local');

            if (!$path) {
                return back()->withErrors(['file' => 'Failed to save file. Check storage permissions.']);
            }

            $validated['filename'] = $filename;
            $validated['size'] = $file->getSize();

            // Store poster image if uploaded
            if ($request->hasFile('poster')) {
                $poster = $request->file('poster');
                $posterName = time() . '_poster_' . Str::slug($request->title) . '.' . $poster->getClientOriginalExtension();
                
                // Create directory if it doesn't exist
                $destPath = public_path('posters');
                if (!file_exists($destPath)) {
                    mkdir($destPath, 0755, true);
                }
                
                if ($poster->move($destPath, $posterName)) {
                    $validated['poster_url'] = '/posters/' . $posterName;
                }
            }

            $media = Media::create($validated);

            // Trigger multi-resolution conversion
            ProcessMediaVersions::dispatch($media);

            return redirect()->route('admin.media.index')->with('success', 'Media uploaded successfully! Conversion is starting in the background.');
        } catch (\Exception $e) {
            return back()->withErrors(['file' => 'Upload failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Show edit form
     */
    public function edit(Media $medium)
    {
        $categories = Category::all();
        return view('admin.media.edit', compact('medium', 'categories'));
    }

    /**
     * Update media
     */
    public function update(Request $request, Media $medium)
    {
        // Increase execution time for large file uploads (10 minutes)
        set_time_limit(600);
        
        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'type' => 'required|in:movie,series',
            'category_id' => 'required|exists:categories,id',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'duration' => 'nullable|integer|min:1',
            'year' => 'nullable|string',
            'director' => 'nullable|string',
            'cast' => 'nullable|string',
            'episodes' => 'nullable|integer|min:1',
        ]);

        $validated['is_processed'] = true;

        // Handle poster upload
        if ($request->hasFile('poster')) {
            // Delete old poster if exists
            if ($medium->poster_url && file_exists(public_path($medium->poster_url))) {
                @unlink(public_path($medium->poster_url));
            }
            
            $poster = $request->file('poster');
            $posterName = time() . '_poster_' . Str::slug($request->title) . '.' . $poster->getClientOriginalExtension();
            
            // Create directory if it doesn't exist
            $destPath = public_path('posters');
            if (!file_exists($destPath)) {
                mkdir($destPath, 0755, true);
            }
            
            if ($poster->move($destPath, $posterName)) {
                $validated['poster_url'] = '/posters/' . $posterName;
            }
        }

        $medium->update($validated);

        return redirect()->route('admin.media.index')->with('success', 'Media updated successfully');
    }

    /**
     * Delete media
     */
    public function destroy(Media $medium)
    {
        \Illuminate\Support\Facades\Storage::delete('media/' . $medium->filename);
        $medium->delete();

        return redirect()->route('admin.media.index')->with('success', 'Media deleted successfully');
    }
}
