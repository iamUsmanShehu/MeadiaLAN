@extends('admin.layouts.app')

@section('title', 'PINs')

@section('page-title', 'PIN Management')

@section('content')
<div class="flex justify-between items-center mb-8">
    <h2 class="text-2xl font-bold">PINs</h2>
    <div class="space-x-3">
        <a href="{{ route('admin.pins.create') }}" class="inline-block bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg">
            Create Single PIN
        </a>
        <a href="{{ route('admin.pins.create-bulk') }}" class="inline-block bg-green-600 hover:bg-green-700 px-4 py-2 rounded-lg">
            Bulk Generate
        </a>
        <a href="{{ route('admin.pins.export') }}" class="inline-block bg-purple-600 hover:bg-purple-700 px-4 py-2 rounded-lg">
            Export CSV
        </a>
    </div>
</div>

@if(session('success'))
    <div class="mb-6 p-4 bg-green-900 text-green-200 rounded-lg">
        {{ session('success') }}
    </div>
@endif

<div class="bg-gray-800 rounded-lg border border-gray-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-700 bg-gray-700">
                    <th class="px-6 py-3 text-left text-sm font-semibold">PIN Code</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Status</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Downloads</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Created</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pins as $pin)
                    <tr class="border-b border-gray-700 hover:bg-gray-700">
                        <td class="px-6 py-4 font-mono">{{ $pin->pin_code }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $pin->status === 'active' ? 'bg-green-900 text-green-200' : 'bg-red-900 text-red-200' }}">
                                {{ ucfirst($pin->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">{{ $pin->used_downloads }}/{{ $pin->max_downloads }}</td>
                        <td class="px-6 py-4 text-gray-400 text-sm">{{ $pin->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4 space-x-2">
                            <a href="{{ route('admin.pins.show', $pin) }}" class="text-blue-500 hover:text-blue-400 text-sm">View</a>
                            <a href="{{ route('admin.pins.print', $pin) }}" class="text-green-500 hover:text-green-400 text-sm">Print</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="px-6 py-4 text-gray-400 text-center" colspan="5">No PINs found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-6">
    {{ $pins->links() }}
</div>
@endsection
