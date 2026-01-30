@extends('admin.layouts.app')

@section('title', 'PIN Details - ' . $pin->pin_code)

@section('page-title', 'PIN Details: ' . $pin->pin_code)

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- PIN Info -->
    <div class="lg:col-span-2">
        <div class="bg-gray-800 rounded-lg border border-gray-700 p-6 mb-6">
            <h2 class="text-2xl font-bold mb-6">PIN Information</h2>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <p class="text-gray-400 text-sm mb-1">PIN Code</p>
                    <p class="text-xl font-mono font-bold">{{ $pin->pin_code }}</p>
                </div>

                <div>
                    <p class="text-gray-400 text-sm mb-1">Status</p>
                    <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold {{ $pin->status === 'active' ? 'bg-green-900 text-green-200' : 'bg-red-900 text-red-200' }}">
                        {{ ucfirst($pin->status) }}
                    </span>
                </div>

                <div>
                    <p class="text-gray-400 text-sm mb-1">Downloads Used</p>
                    <p class="text-xl font-bold">{{ $pin->used_downloads }} / {{ $pin->max_downloads }}</p>
                </div>

                <div>
                    <p class="text-gray-400 text-sm mb-1">Remaining</p>
                    <p class="text-xl font-bold text-blue-400">{{ $pin->getRemainingDownloads() }}</p>
                </div>

                <div>
                    <p class="text-gray-400 text-sm mb-1">Created</p>
                    <p class="text-sm">{{ $pin->created_at->format('M d, Y H:i:s') }}</p>
                </div>

                <div>
                    <p class="text-gray-400 text-sm mb-1">Updated</p>
                    <p class="text-sm">{{ $pin->updated_at->format('M d, Y H:i:s') }}</p>
                </div>
            </div>

            @if($pin->status === 'active')
                <form action="{{ route('admin.pins.revoke', $pin) }}" method="post" class="mt-6">
                    @csrf
                    <button type="submit" class="bg-red-600 hover:bg-red-700 px-6 py-2 rounded-lg font-semibold" onclick="return confirm('Are you sure?')">
                        Revoke PIN
                    </button>
                </form>
            @endif
        </div>

        <!-- Downloads Log -->
        <div class="bg-gray-800 rounded-lg border border-gray-700 p-6">
            <h2 class="text-xl font-bold mb-4">Download History</h2>

            @if($downloads->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-700">
                                <th class="text-left px-4 py-2 text-gray-400">Media</th>
                                <th class="text-left px-4 py-2 text-gray-400">Downloaded</th>
                                <th class="text-left px-4 py-2 text-gray-400">IP</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($downloads as $download)
                                <tr class="border-b border-gray-700 hover:bg-gray-700">
                                    <td class="px-4 py-2">{{ $download->media->title }}</td>
                                    <td class="px-4 py-2 text-gray-400">{{ $download->downloaded_at->diffForHumans() }}</td>
                                    <td class="px-4 py-2 text-gray-400 font-mono text-xs">{{ $download->ip_address }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($downloads->hasPages())
                    <div class="mt-4">
                        {{ $downloads->links() }}
                    </div>
                @endif
            @else
                <p class="text-gray-400">No downloads yet</p>
            @endif
        </div>
    </div>

    <!-- Actions -->
    <div class="space-y-6">
        <div class="bg-gray-800 rounded-lg border border-gray-700 p-6">
            <h3 class="text-lg font-bold mb-4">Actions</h3>
            <div class="space-y-2">
                <a href="{{ route('admin.pins.print', $pin) }}" class="block bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg text-center font-semibold">
                    Print PIN
                </a>
                <a href="{{ route('admin.pins.index') }}" class="block bg-gray-700 hover:bg-gray-600 px-4 py-2 rounded-lg text-center font-semibold">
                    Back to List
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
