@extends('admin.layouts.app')

@section('title', 'Edit Media')

@section('page-title', 'Edit Media: ' . $medium->title)

@section('content')
<div class="max-w-4xl mx-auto px-4 py-6">
    <div class="card">
        <div class="flex items-center mb-6">
            <div class="h-12 w-12 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center text-white text-xl font-bold mr-4">
                ✏️
            </div>
            <h2 class="text-2xl sm:text-3xl font-bold">{{ $medium->title }}</h2>
        </div>

        @if($errors->any())
            <div class="mb-6 p-4 bg-red-900 text-red-200 rounded-lg border border-red-700">
                <p class="font-semibold mb-2">❌ Update Failed</p>
                <ul class="list-disc list-inside text-sm space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.media.update', $medium) }}" method="post" enctype="multipart/form-data" class="space-y-6" id="editForm">
            @csrf
            @method('PUT')

            <!-- Two Column Layout -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Left Column - Main Fields -->
                <div class="md:col-span-2 space-y-6">
                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-sm font-semibold mb-2 text-blue-400">📝 Title</label>
                        <input 
                            type="text" 
                            id="title" 
                            name="title"
                            value="{{ old('title', $medium->title) }}"
                            class="w-full px-4 py-3 bg-gray-700 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('title') ring-2 ring-red-500 @enderror min-h-[48px]"
                            required
                        >
                        @error('title')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-semibold mb-2 text-blue-400">📄 Description</label>
                        <textarea 
                            id="description" 
                            name="description"
                            rows="4"
                            class="w-full px-4 py-3 bg-gray-700 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') ring-2 ring-red-500 @enderror resize-none"
                        >{{ old('description', $medium->description) }}</textarea>
                        @error('description')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Type and Category -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="type" class="block text-sm font-semibold mb-2 text-blue-400">🎬 Type</label>
                            <select 
                                id="type" 
                                name="type"
                                class="w-full px-4 py-3 bg-gray-700 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('type') ring-2 ring-red-500 @enderror min-h-[48px]"
                                required
                            >
                                <option value="movie" {{ old('type', $medium->type) === 'movie' ? 'selected' : '' }}>🎥 Movie</option>
                                <option value="series" {{ old('type', $medium->type) === 'series' ? 'selected' : '' }}>📺 Series</option>
                            </select>
                            @error('type')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="category_id" class="block text-sm font-semibold mb-2 text-blue-400">📂 Category</label>
                            <select 
                                id="category_id" 
                                name="category_id"
                                class="w-full px-4 py-3 bg-gray-700 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('category_id') ring-2 ring-red-500 @enderror min-h-[48px]"
                                required
                            >
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $medium->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Year and Director -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="year" class="block text-sm font-semibold mb-2 text-blue-400">📅 Year</label>
                            <input 
                                type="text" 
                                id="year" 
                                name="year"
                                value="{{ old('year', $medium->year) }}"
                                class="w-full px-4 py-3 bg-gray-700 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 min-h-[48px]"
                            >
                        </div>

                        <div>
                            <label for="director" class="block text-sm font-semibold mb-2 text-blue-400">👤 Director</label>
                            <input 
                                type="text" 
                                id="director" 
                                name="director"
                                value="{{ old('director', $medium->director) }}"
                                class="w-full px-4 py-3 bg-gray-700 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 min-h-[48px]"
                            >
                        </div>
                    </div>

                    <!-- Duration and Episodes -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="duration" class="block text-sm font-semibold mb-2 text-blue-400">⏱️ Duration (minutes)</label>
                            <input 
                                type="number" 
                                id="duration" 
                                name="duration"
                                value="{{ old('duration', $medium->duration) }}"
                                class="w-full px-4 py-3 bg-gray-700 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 min-h-[48px]"
                            >
                        </div>

                        <div>
                            <label for="episodes" class="block text-sm font-semibold mb-2 text-blue-400">🎞️ Episodes</label>
                            <input 
                                type="number" 
                                id="episodes" 
                                name="episodes"
                                value="{{ old('episodes', $medium->episodes) }}"
                                class="w-full px-4 py-3 bg-gray-700 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 min-h-[48px]"
                            >
                        </div>
                    </div>

                    <!-- Cast -->
                    <div>
                        <label for="cast" class="block text-sm font-semibold mb-2 text-blue-400">🎭 Cast</label>
                        <input 
                            type="text" 
                            id="cast" 
                            name="cast"
                            value="{{ old('cast', $medium->cast) }}"
                            class="w-full px-4 py-3 bg-gray-700 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 min-h-[48px]"
                        >
                    </div>
                </div>

                <!-- Right Column - Poster -->
                <div class="md:col-span-1">
                    <div class="sticky top-6">
                        <div class="bg-gray-700 p-6 rounded-lg border border-gray-600">
                            <label for="poster" class="block text-sm font-semibold mb-3 text-blue-400">🎨 Poster</label>
                            
                            <!-- Current Poster -->
                            @if($medium->poster_url)
                                <div class="mb-4">
                                    <img id="currentPoster" src="{{ asset($medium->poster_url) }}" alt="Poster" class="w-full h-auto rounded-lg object-cover border border-gray-600">
                                    <p class="text-xs text-gray-400 mt-2">Current poster</p>
                                </div>
                            @endif

                            <!-- New Poster Preview -->
                            <div id="posterPreview" class="mb-4 hidden">
                                <img id="posterImg" src="" alt="Preview" class="w-full h-auto rounded-lg object-cover">
                                <p class="text-xs text-gray-400 mt-2">New poster preview</p>
                            </div>

                            <!-- File Input -->
                            <input 
                                type="file" 
                                id="poster" 
                                name="poster"
                                accept="image/jpeg,image/png,image/jpg,image/gif"
                                class="w-full px-3 py-3 bg-gray-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 min-h-[48px] text-sm @error('poster') ring-2 ring-red-500 @enderror"
                            >
                            
                            <p class="text-gray-400 text-xs mt-3">
                                📸 JPEG, PNG, GIF<br>
                                Max: 5MB<br>
                                Leave empty to keep current
                            </p>

                            @error('poster')
                                <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex flex-col sm:flex-row gap-3 pt-2">
                <button type="submit" id="submitBtn" class="btn-primary flex-1 min-h-[48px] text-base font-semibold" style="min-width: 200px;">
                    <span id="submitText">✅ Save Changes</span>
                    <span id="submitSpinner" class="hidden ml-2 inline-block">⏳</span>
                </button>
                <a href="{{ route('admin.media.index') }}" class="btn-secondary flex-1 text-center min-h-[48px] flex items-center justify-center text-base font-semibold">
                    ❌ Cancel
                </a>
            </div>
        </form>
    </div>
</div>

@section('extra-js')
<script>
// Poster preview handler
document.getElementById('poster').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(event) {
            const posterImg = document.getElementById('posterImg');
            posterImg.src = event.target.result;
            document.getElementById('posterPreview').classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }
});

// Form submission with progress tracking
document.getElementById('editForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    const submitSpinner = document.getElementById('submitSpinner');
    const form = this;
    
    submitBtn.disabled = true;
    submitText.textContent = 'Starting update...';
    submitSpinner.classList.remove('hidden');
    
    // Create FormData from the form
    const formData = new FormData(form);
    
    // Create XMLHttpRequest for progress tracking
    const xhr = new XMLHttpRequest();
    
    // Track upload progress
    xhr.upload.addEventListener('progress', function(e) {
        if (e.lengthComputable) {
            const percentComplete = Math.round((e.loaded / e.total) * 100);
            submitText.textContent = `Updating... ${percentComplete}%`;
        }
    });
    
    // Handle completion
    xhr.addEventListener('load', function() {
        if (xhr.status >= 200 && xhr.status < 400) {
            submitText.textContent = '✅ Update complete! Redirecting...';
            setTimeout(() => {
                window.location.href = '{{ route("admin.media.index") }}';
            }, 1000);
        } else {
            submitText.textContent = '❌ Update failed. Try again.';
            submitBtn.disabled = false;
            submitSpinner.classList.add('hidden');
            alert('Update failed with status: ' + xhr.status);
        }
    });
    
    // Handle errors
    xhr.addEventListener('error', function() {
        submitText.textContent = '❌ Update error. Try again.';
        submitBtn.disabled = false;
        submitSpinner.classList.add('hidden');
        alert('Network error during update');
    });
    
    // Send the request
    xhr.open('POST', form.action, true);
    xhr.send(formData);
});
</script>
@endsection
