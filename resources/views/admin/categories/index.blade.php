@extends('admin.layouts.app')

@section('title', 'Categories')

@section('page-title', 'Category Management')

@section('content')
<div class="flex justify-between items-center mb-8">
    <h2 class="text-2xl font-bold">Categories</h2>
    <a href="{{ route('admin.categories.create') }}" class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg font-semibold">
        Add Category
    </a>
</div>

@if(session('success'))
    <div class="mb-6 p-4 bg-green-900 text-green-200 rounded-lg">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="mb-6 p-4 bg-red-900 text-red-200 rounded-lg">
        {{ session('error') }}
    </div>
@endif

<div class="bg-gray-800 rounded-lg border border-gray-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-700 bg-gray-700">
                    <th class="px-6 py-3 text-left text-sm font-semibold">Name</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Description</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Media Count</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                    <tr class="border-b border-gray-700 hover:bg-gray-700">
                        <td class="px-6 py-4 font-semibold">{{ $category->name }}</td>
                        <td class="px-6 py-4 text-gray-400">{{ Str::limit($category->description, 50) }}</td>
                        <td class="px-6 py-4">{{ $category->media_count }}</td>
                        <td class="px-6 py-4 space-x-2">
                            <a href="{{ route('admin.categories.edit', $category) }}" class="text-blue-500 hover:text-blue-400 text-sm">Edit</a>
                            @if(!$category->media()->exists())
                                <form action="{{ route('admin.categories.destroy', $category) }}" method="post" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-400 text-sm" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="px-6 py-4 text-gray-400 text-center" colspan="4">No categories found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-6">
    {{ $categories->links() }}
</div>
@endsection
