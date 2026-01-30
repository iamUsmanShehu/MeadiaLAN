@extends('admin.layouts.app')

@section('title', 'Print PIN - ' . $pin->pin_code)

@section('content')
<div class="flex justify-between items-center mb-8">
    <h2 class="text-2xl font-bold">Print PIN</h2>
    <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg font-semibold">
        Print / Save as PDF
    </button>
</div>

<!-- Printable Card -->
<div class="bg-white text-gray-900 p-8 rounded-lg max-w-md mx-auto print:p-0 print:rounded-none">
    <div class="text-center border-4 border-gray-900 p-8">
        <h1 class="text-2xl font-bold mb-2">MediaLAN</h1>
        <p class="text-gray-600 mb-6">Download Access PIN</p>

        <div class="bg-gray-100 p-6 mb-6 rounded-lg">
            <p class="text-gray-600 text-sm mb-2">Your PIN Code:</p>
            <p class="text-4xl font-mono font-bold text-blue-600 tracking-widest">{{ $pin->pin_code }}</p>
        </div>

        <div class="text-sm text-gray-600 space-y-2 mb-6">
            <p><strong>Downloads Included:</strong> {{ $pin->max_downloads }}</p>
            <p><strong>Status:</strong> {{ ucfirst($pin->status) }}</p>
            <p><strong>Created:</strong> {{ $pin->created_at->format('M d, Y') }}</p>
        </div>

        <div class="border-t-2 border-gray-300 pt-6 text-center">
            <p class="text-xs text-gray-600 mb-4">
                Enter this PIN on the MediaLAN website to download movies and series.
            </p>
            <p class="text-xs text-gray-600 font-semibold">
                Each PIN allows exactly {{ $pin->max_downloads }} downloads before expiring.
            </p>
        </div>
    </div>
</div>

<div class="mt-8 text-center print:hidden">
    <a href="{{ route('admin.pins.index') }}" class="text-blue-500 hover:text-blue-400">
        Back to Pins List
    </a>
</div>
@endsection

@section('extra-css')
<style>
    @media print {
        body {
            background: white;
            margin: 0;
            padding: 0;
        }
        .print\:hidden {
            display: none !important;
        }
    }
</style>
@endsection
