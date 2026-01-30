@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')

@section('page-title', 'Dashboard')

@section('content')
<div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 md:gap-6 mb-8">
    <!-- Stats -->
    <div class="card">
        <p class="text-gray-400 text-xs sm:text-sm mb-2">Total PINs</p>
        <p class="text-2xl sm:text-3xl font-bold">{{ $totalPins }}</p>
        <p class="text-green-500 text-xs sm:text-sm mt-2">{{ $activePins }} active</p>
    </div>

    <div class="card">
        <p class="text-gray-400 text-xs sm:text-sm mb-2">Expired PINs</p>
        <p class="text-2xl sm:text-3xl font-bold">{{ $expiredPins }}</p>
    </div>

    <div class="card">
        <p class="text-gray-400 text-xs sm:text-sm mb-2">Total Media</p>
        <p class="text-2xl sm:text-3xl font-bold">{{ $totalMedia }}</p>
        <p class="text-blue-500 text-xs sm:text-sm mt-2">{{ $categories }} categories</p>
    </div>

    <div class="card">
        <p class="text-gray-400 text-xs sm:text-sm mb-2">Total Downloads</p>
        <p class="text-2xl sm:text-3xl font-bold">{{ $totalDownloads }}</p>
    </div>
</div>

<!-- Quick Actions -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4 md:gap-6 mb-8">
    <a href="{{ route('admin.pins.create') }}" class="block bg-blue-600 hover:bg-blue-700 rounded-lg p-4 sm:p-6 transition active:scale-95 min-h-[100px] flex items-center">
        <div>
            <p class="font-semibold text-sm sm:text-base mb-1">Create Single PIN</p>
            <p class="text-xs sm:text-sm text-blue-200">Generate a new download PIN</p>
        </div>
    </a>

    <a href="{{ route('admin.pins.create-bulk') }}" class="block bg-blue-600 hover:bg-blue-700 rounded-lg p-4 sm:p-6 transition active:scale-95 min-h-[100px] flex items-center">
        <div>
            <p class="font-semibold text-sm sm:text-base mb-1">Bulk PIN Generation</p>
            <p class="text-xs sm:text-sm text-blue-200">Create multiple PINs at once</p>
        </div>
    </a>

    <a href="{{ route('admin.media.create') }}" class="block bg-blue-600 hover:bg-blue-700 rounded-lg p-4 sm:p-6 transition active:scale-95 min-h-[100px] flex items-center">
        <div>
            <p class="font-semibold text-sm sm:text-base mb-1">Upload Media</p>
            <p class="text-xs sm:text-sm text-blue-200">Add new movie or series</p>
        </div>
    </a>
</div>

<!-- Recent Downloads -->
<div class="card">
    <h2 class="text-lg sm:text-xl font-bold mb-4">Recent Downloads</h2>
    <div class="overflow-x-auto -mx-4 sm:mx-0">
        <table class="w-full text-xs sm:text-sm">
            <thead>
                <tr class="border-b border-gray-700">
                    <th class="text-left px-4 py-2 sm:py-3 text-gray-400">Media</th>
                    <th class="text-left px-4 py-2 sm:py-3 text-gray-400">PIN</th>
                    <th class="text-left px-4 py-2 sm:py-3 text-gray-400">Time</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentDownloads as $download)
                    <tr class="border-b border-gray-700 hover:bg-gray-700">
                        <td class="px-4 py-2 sm:py-3 truncate">{{ $download->media->title }}</td>
                        <td class="px-4 py-2 sm:py-3 truncate">
                            <a href="{{ route('admin.pins.show', $download->pin) }}" class="text-blue-500 hover:text-blue-400 text-xs sm:text-sm">
                                {{ $download->pin->pin_code }}
                            </a>
                        </td>
                        <td class="px-4 py-2 sm:py-3 text-gray-400 text-xs sm:text-sm">{{ $download->downloaded_at->diffForHumans() }}</td>
                    </tr>
                @empty
                    <tr>
                        <td class="px-4 py-2 sm:py-3 text-gray-400 col-span-3">No downloads yet</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Recent PINs -->
<div class="card mt-8">
    <h2 class="text-lg sm:text-xl font-bold mb-4">Recent PINs</h2>
    <div class="overflow-x-auto -mx-4 sm:mx-0">
        <table class="w-full text-xs sm:text-sm">
            <thead>
                <tr class="border-b border-gray-700">
                    <th class="text-left px-4 py-2 sm:py-3 text-gray-400">PIN Code</th>
                    <th class="text-left px-4 py-2 sm:py-3 text-gray-400">Status</th>
                    <th class="text-left px-4 py-2 sm:py-3 text-gray-400">Downloads</th>
                    <th class="text-left px-4 py-2 sm:py-3 text-gray-400">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentPins as $pin)
                    <tr class="border-b border-gray-700 hover:bg-gray-700">
                        <td class="px-4 py-2 sm:py-3 font-mono text-xs sm:text-sm">{{ $pin->pin_code }}</td>
                        <td class="px-4 py-2 sm:py-3">
                            <span class="inline-block px-2 py-1 rounded text-xs sm:text-sm {{ $pin->status === 'active' ? 'bg-green-900 text-green-200' : 'bg-red-900 text-red-200' }}">
                                {{ ucfirst($pin->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-2 sm:py-3 text-xs sm:text-sm">{{ $pin->used_downloads }}/{{ $pin->max_downloads }}</td>
                        <td class="px-4 py-2 sm:py-3">
                            <a href="{{ route('admin.pins.show', $pin) }}" class="text-blue-500 hover:text-blue-400 text-xs sm:text-sm">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="px-4 py-2 sm:py-3 text-gray-400 text-xs sm:text-sm">No PINs yet</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
