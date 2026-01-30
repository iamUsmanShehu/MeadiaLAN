@extends('layouts.app')

@section('title', $medium->title . ' - MediaLAN')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8">
        <!-- Poster -->
        <div class="col-span-1">
            @if($medium->poster_url)
                <img src="{{ $medium->poster_url }}" alt="{{ $medium->title }}" class="w-full rounded-lg border border-gray-700">
            @else
                <div class="w-full aspect-video bg-gray-700 rounded-lg flex items-center justify-center border border-gray-700">
                    <span class="text-gray-500 text-center px-4">No Poster</span>
                </div>
            @endif
        </div>

        <!-- Details -->
        <div class="md:col-span-2">
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold mb-4 sm:mb-6">{{ $medium->title }}</h1>
            
            <div class="space-y-3 sm:space-y-4 mb-6 sm:mb-8">
                <div>
                    <p class="text-gray-400 text-xs sm:text-sm">Type</p>
                    <p class="text-base sm:text-lg font-semibold">{{ ucfirst($medium->type) }}</p>
                </div>

                <div>
                    <p class="text-gray-400 text-xs sm:text-sm">Category</p>
                    <a href="{{ route('category', $medium->category) }}" class="text-base sm:text-lg font-semibold text-blue-500 hover:text-blue-400 active:text-blue-600">
                        {{ $medium->category->name }}
                    </a>
                </div>

                @if($medium->year)
                    <div>
                        <p class="text-gray-400 text-xs sm:text-sm">Year</p>
                        <p class="text-base sm:text-lg font-semibold">{{ $medium->year }}</p>
                    </div>
                @endif

                @if($medium->director)
                    <div>
                        <p class="text-gray-400 text-xs sm:text-sm">Director</p>
                        <p class="text-base sm:text-lg font-semibold">{{ $medium->director }}</p>
                    </div>
                @endif

                @if($medium->cast)
                    <div>
                        <p class="text-gray-400 text-xs sm:text-sm">Cast</p>
                        <p class="text-base sm:text-lg font-semibold line-clamp-3">{{ $medium->cast }}</p>
                    </div>
                @endif

                @if($medium->duration)
                    <div>
                        <p class="text-gray-400 text-xs sm:text-sm">Duration</p>
                        <p class="text-base sm:text-lg font-semibold">{{ $medium->duration }} minutes</p>
                    </div>
                @endif

                @if($medium->episodes)
                    <div>
                        <p class="text-gray-400 text-xs sm:text-sm">Episodes</p>
                        <p class="text-base sm:text-lg font-semibold">{{ $medium->episodes }}</p>
                    </div>
                @endif

                <div>
                    <p class="text-gray-400 text-xs sm:text-sm">File Size</p>
                    <p class="text-base sm:text-lg font-semibold">{{ $medium->getFormattedSizeAttribute() }}</p>
                </div>
            </div>

            @if($medium->description)
                <div class="mb-6 sm:mb-8">
                    <h3 class="text-lg sm:text-xl font-semibold mb-2">Description</h3>
                    <p class="text-gray-300 leading-relaxed text-sm sm:text-base">{{ $medium->description }}</p>
                </div>
            @endif

            <!-- Resolution Selection -->
            @if($medium->versions->count() > 0)
                <div class="mb-6">
                    <label for="resolution-select" class="block text-sm font-semibold mb-2 text-gray-400">Select Resolution</label>
                    <div class="relative">
                        <select id="resolution-select" class="w-full sm:w-64 px-4 py-3 bg-gray-800 text-white rounded-lg border border-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 appearance-none">
                            <option value="original">Original ({{ $medium->getFormattedSizeAttribute() }})</option>
                            @foreach($medium->versions as $version)
                                <option value="{{ $version->id }}">
                                    {{ $version->resolution }} ({{ $version->getFormattedSizeAttribute() }})
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-gray-400 sm:left-64 sm:-translate-x-full">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Download Button -->
            <a id="download-link" href="{{ route('download.verify', $medium) }}" class="btn-primary inline-block w-full sm:w-auto text-center">
                Download with PIN
            </a>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const select = document.getElementById('resolution-select');
                    const link = document.getElementById('download-link');
                    
                    if (select && link) {
                        const originalHref = link.href;
                        
                        select.addEventListener('change', function() {
                            if (this.value === 'original') {
                                link.href = originalHref;
                            } else {
                                link.href = originalHref + '?version=' + this.value;
                            }
                        });
                    }
                });
            </script>
        </div>
    </div>
</div>
@endsection
