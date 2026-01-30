@extends('admin.layouts.app')

@section('title', 'Print Bulk PINs')

@section('content')
<div class="flex justify-between items-center mb-8">
    <h2 class="text-2xl font-bold">Print PINs</h2>
    <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg font-semibold">
        Print / Save as PDF
    </button>
</div>

<!-- Printable Cards -->
<div class="grid grid-cols-2 gap-4 print:gap-2">
    @foreach($pins as $pin)
        <div class="break-after-page">
            <div class="bg-white text-gray-900 p-6 rounded-lg border-4 border-gray-900">
                <div class="text-center">
                    <h3 class="text-xl font-bold mb-2">MediaLAN</h3>
                    <p class="text-gray-600 text-sm mb-4">Download Access PIN</p>

                    <div class="bg-gray-100 p-4 mb-4 rounded-lg">
                        <p class="text-gray-600 text-xs mb-1">PIN Code:</p>
                        <p class="text-3xl font-mono font-bold text-blue-600 tracking-wider">{{ $pin->pin_code }}</p>
                    </div>

                    <div class="text-xs text-gray-600 space-y-1 mb-4">
                        <p><strong>Downloads:</strong> {{ $pin->max_downloads }}</p>
                        <p><strong>Created:</strong> {{ $pin->created_at->format('M d, Y') }}</p>
                    </div>

                    <p class="text-xs text-gray-600 border-t border-gray-300 pt-4">
                        Enter this PIN to download movies and series
                    </p>
                </div>
            </div>
        </div>
    @endforeach
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
        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }
        .break-after-page {
            page-break-after: auto;
        }
        .print\:hidden {
            display: none !important;
        }
    }
</style>
@endsection
