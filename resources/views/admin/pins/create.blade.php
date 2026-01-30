@extends('admin.layouts.app')

@section('title', 'Create PIN')

@section('page-title', 'Create PIN')

@section('content')
<div class="max-w-2xl">
    <div class="bg-gray-800 rounded-lg border border-gray-700 p-6">
        <h2 class="text-2xl font-bold mb-6">Create New PIN</h2>

        <form action="{{ route('admin.pins.store') }}" method="post" class="space-y-6">
            @csrf

            <div>
                <label for="max_downloads" class="block text-sm font-medium mb-2">Max Downloads</label>
                <input 
                    type="number" 
                    id="max_downloads" 
                    name="max_downloads"
                    value="{{ old('max_downloads', 3) }}"
                    min="1"
                    max="10"
                    class="w-full px-4 py-2 bg-gray-700 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required
                >
                <p class="text-gray-400 text-sm mt-1">How many times this PIN can be used</p>
                @error('max_downloads')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex space-x-4">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 px-6 py-2 rounded-lg font-semibold">
                    Create PIN
                </button>
                <a href="{{ route('admin.pins.index') }}" class="bg-gray-700 hover:bg-gray-600 px-6 py-2 rounded-lg font-semibold">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
