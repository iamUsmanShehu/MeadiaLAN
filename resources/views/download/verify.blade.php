@extends('layouts.app')

@section('title', 'Download - ' . $medium->title)

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    <div class="bg-gray-800 rounded-lg p-4 sm:p-8 border border-gray-700">
        <h1 class="text-2xl sm:text-3xl font-bold mb-2">Download {{ $medium->title }}</h1>
        <p class="text-gray-400 mb-6 sm:mb-8 text-sm sm:text-base">Enter your PIN to download this {{ $medium->type }}</p>

        <div class="mb-6 sm:mb-8 p-4 bg-gray-700 rounded-lg">
            @php
                $versionId = request('version');
                $version = $versionId ? \App\Models\MediaVersion::find($versionId) : null;
                $displaySize = $version ? $version->getFormattedSizeAttribute() : $medium->getFormattedSizeAttribute();
                $displayName = $version ? "{$medium->title} ({$version->resolution})" : $medium->title;
            @endphp
            <p class="text-sm sm:text-base text-gray-400">Resolution: <strong>{{ $version ? $version->resolution : 'Original' }}</strong></p>
            <p class="text-sm sm:text-base text-gray-400">File size: <strong>{{ $displaySize }}</strong></p>
        </div>

        @if($errors->any())
            <div class="mb-6 p-4 bg-red-900 text-red-200 rounded-lg border border-red-700">
                <ul class="list-disc list-inside text-sm sm:text-base">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('download.verify.post', $medium) }}" method="post">
            @csrf
            @if(request('version'))
                <input type="hidden" name="version" value="{{ request('version') }}">
            @endif
            <div class="mb-6">
                <label for="pin" class="block text-sm sm:text-base font-medium mb-2">PIN Code</label>
                <input 
                    type="text" 
                    id="pin" 
                    name="pin" 
                    placeholder="XXXX-XXXX-XXXX-XXXX"
                    class="w-full px-4 py-3 bg-gray-700 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('pin') ring-2 ring-red-500 @enderror min-h-[48px] text-base"
                    required
                    autofocus
                >
                @error('pin')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="btn-primary w-full">
                Download
            </button>
        </form>

        <p class="text-gray-400 text-xs sm:text-sm mt-6 sm:mt-8 text-center">
            Don't have a PIN? Contact admin to get access.
        </p>

        <a href="{{ route('media.show', $medium) }}" class="block text-center text-blue-500 hover:text-blue-400 active:text-blue-600 mt-4 text-sm sm:text-base font-medium transition">
            ← Back to media details
        </a>
    </div>
</div>
@endsection
