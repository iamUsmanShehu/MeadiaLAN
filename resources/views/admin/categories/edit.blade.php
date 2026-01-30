@extends('admin.layouts.app')

@section('title', 'Edit Category')

@section('page-title', 'Edit Category: ' . $category->name)

@section('content')
<div class="max-w-2xl">
    <div class="bg-gray-800 rounded-lg border border-gray-700 p-6">
        <h2 class="text-2xl font-bold mb-6">Edit Category</h2>

        <form action="{{ route('admin.categories.update', $category) }}" method="post" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-sm font-medium mb-2">Category Name</label>
                <input 
                    type="text" 
                    id="name" 
                    name="name"
                    value="{{ old('name', $category->name) }}"
                    class="w-full px-4 py-2 bg-gray-700 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') ring-2 ring-red-500 @enderror"
                    required
                >
                @error('name')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="block text-sm font-medium mb-2">Description</label>
                <textarea 
                    id="description" 
                    name="description"
                    rows="4"
                    class="w-full px-4 py-2 bg-gray-700 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') ring-2 ring-red-500 @enderror"
                >{{ old('description', $category->description) }}</textarea>
                @error('description')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex space-x-4">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 px-6 py-2 rounded-lg font-semibold">
                    Update Category
                </button>
                <a href="{{ route('admin.categories.index') }}" class="bg-gray-700 hover:bg-gray-600 px-6 py-2 rounded-lg font-semibold">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
