@extends('admin.layouts.app')

@section('title', 'Upload Media')

@section('page-title', 'Upload Media')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-6">
    <div class="card mb-8">
        <div class="flex items-center mb-6">
            <div class="h-12 w-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center text-white text-xl font-bold mr-4">
                📤
            </div>

            <h2 class="text-2xl sm:text-3xl font-bold">Upload Media</h2>
        </div>

        @if($errors->any())
            <div class="mb-6 p-4 bg-red-900 text-red-200 rounded-lg border border-red-700">
                <p class="font-semibold mb-2">❌ Upload Failed</p>
                <ul class="list-disc list-inside text-sm space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-900 text-red-200 rounded-lg border border-red-700">
                <p>{{ session('error') }}</p>
            </div>
        @endif

        <form action="{{ route('admin.media.store') }}" method="post" enctype="multipart/form-data" class="space-y-6" id="mediaForm">
            @csrf

            <!-- Two Column Layout -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Left Column - Main Fields -->
                <div class="md:col-span-2 space-y-6">
                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-sm font-semibold mb-2 text-blue-400">📝 Title <span class="text-red-500">*</span></label>
                        <input 
                            type="text" 
                            id="title" 
                            name="title"
                            value="{{ old('title') }}"
                            class="w-full px-4 py-3 bg-gray-700 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('title') ring-2 ring-red-500 @enderror min-h-[48px] text-base"
                            required
                            placeholder="Enter media title"
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
                            class="w-full px-4 py-3 bg-gray-700 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') ring-2 ring-red-500 @enderror text-base resize-none"
                            placeholder="Enter media description"
                        >{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Type and Category -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="type" class="block text-sm font-semibold mb-2 text-blue-400">🎬 Type <span class="text-red-500">*</span></label>
                            <select 
                                id="type" 
                                name="type"
                                class="w-full px-4 py-3 bg-gray-700 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('type') ring-2 ring-red-500 @enderror min-h-[48px] text-base"
                                required
                            >
                                <option value="">Select type</option>
                                <option value="movie" {{ old('type') === 'movie' ? 'selected' : '' }}>🎥 Movie</option>
                                <option value="series" {{ old('type') === 'series' ? 'selected' : '' }}>📺 Series</option>
                            </select>
                            @error('type')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="category_id" class="block text-sm font-semibold mb-2 text-blue-400">📂 Category <span class="text-red-500">*</span></label>
                            <select 
                                id="category_id" 
                                name="category_id"
                                class="w-full px-4 py-3 bg-gray-700 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('category_id') ring-2 ring-red-500 @enderror min-h-[48px] text-base"
                                required
                            >
                                <option value="">Select category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
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
                                value="{{ old('year') }}"
                                class="w-full px-4 py-3 bg-gray-700 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 min-h-[48px] text-base"
                                placeholder="e.g., 2024"
                                pattern="\d{4}"
                            >
                        </div>

                        <div>
                            <label for="director" class="block text-sm font-semibold mb-2 text-blue-400">👤 Director</label>
                            <input 
                                type="text" 
                                id="director" 
                                name="director"
                                value="{{ old('director') }}"
                                class="w-full px-4 py-3 bg-gray-700 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 min-h-[48px] text-base"
                                placeholder="Director name"
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
                                value="{{ old('duration') }}"
                                class="w-full px-4 py-3 bg-gray-700 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 min-h-[48px] text-base"
                                placeholder="e.g., 120"
                                min="1"
                            >
                        </div>

                        <div>
                            <label for="episodes" class="block text-sm font-semibold mb-2 text-blue-400">🎞️ Episodes</label>
                            <input 
                                type="number" 
                                id="episodes" 
                                name="episodes"
                                value="{{ old('episodes') }}"
                                class="w-full px-4 py-3 bg-gray-700 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 min-h-[48px] text-base"
                                placeholder="For series only"
                                min="1"
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
                            value="{{ old('cast') }}"
                            class="w-full px-4 py-3 bg-gray-700 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 min-h-[48px] text-base"
                            placeholder="Actor names (comma separated)"
                        >
                    </div>
                </div>

                <!-- Right Column - Poster -->
                <div class="md:col-span-1">
                    <div class="sticky top-6">
                        <div class="bg-gray-700 p-6 rounded-lg border border-gray-600">
                            <label for="poster" class="block text-sm font-semibold mb-3 text-blue-400">🎨 Poster Image <span class="text-gray-400 text-xs">(optional)</span></label>
                            
                            <!-- Poster Preview -->
                            <div id="posterPreview" class="mb-4 hidden">
                                <img id="posterImg" src="" alt="Poster preview" class="w-full h-auto rounded-lg object-cover">
                            </div>

                            <!-- File Input -->
                            <input 
                                type="file" 
                                id="poster" 
                                name="poster"
                                accept="image/jpeg,image/png,image/jpg,image/gif"
                                class="w-full px-4 py-3 bg-gray-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 min-h-[48px] text-sm @error('poster') ring-2 ring-red-500 @enderror"
                            >
                            
                            <p class="text-gray-400 text-xs mt-3">
                                📸 JPEG, PNG, GIF<br>
                                Max: 5MB
                            </p>

                            <div id="posterInfo" class="mt-4 hidden p-3 bg-blue-900 text-blue-200 rounded-lg">
                                <p class="text-xs"><strong>Size:</strong> <span id="posterSize"></span></p>
                                <div class="mt-2 w-full bg-blue-800 rounded-full h-2">
                                    <div id="posterProgress" class="bg-blue-400 h-2 rounded-full transition-all" style="width: 0%"></div>
                                </div>
                            </div>

                            @error('poster')
                                <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- File Upload Section -->
            <div class="bg-gradient-to-r from-blue-900 to-blue-800 p-6 rounded-lg border border-blue-700">
                <label for="file" class="block text-sm font-semibold mb-3 text-blue-300">🎬 Media File <span class="text-red-500">*</span></label>
                
                <div class="relative mb-4">
                    <input 
                        type="file" 
                        id="file" 
                        name="file"
                        class="w-full px-4 py-4 bg-gray-700 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 min-h-[56px] text-base cursor-pointer"
                        required
                        accept="video/*,.mp4,.mkv,.avi,.mov,.flv,.m4v,.webm"
                    >
                </div>

                <p class="text-blue-200 text-sm mb-3">
                    ✅ Supported: MP4, MKV, AVI, MOV, FLV, WebM (Max 2GB per file)
                </p>

                <div id="fileInfo" class="hidden p-4 bg-gray-800 rounded-lg border border-gray-600">
                    <div class="flex justify-between items-center mb-3">
                        <p class="text-white text-sm"><strong>📁 File:</strong> <span id="fileName"></span></p>
                        <p class="text-blue-300 text-sm"><strong><span id="fileSize"></span></strong></p>
                    </div>
                    <div class="w-full bg-gray-700 rounded-full h-3 overflow-hidden">
                        <div id="fileProgress" class="bg-gradient-to-r from-blue-500 to-blue-400 h-3 rounded-full transition-all" style="width: 0%"></div>
                    </div>
                    <p class="text-gray-400 text-xs mt-2">Selected: <span id="filePercent">0</span>% of 2GB limit</p>
                </div>

                @error('file')
                    <p class="text-red-300 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Buttons -->
            <div class="flex flex-col sm:flex-row gap-3 pt-2">
                <button type="submit" id="submitBtn" class="btn-primary flex-1 min-h-[48px] text-base font-semibold" style="min-width: 200px;">
                    <span id="submitText">✅ Upload Media</span>
                    <span id="submitSpinner" class="hidden ml-2 inline-block">⏳</span>
                </button>
                <a href="{{ route('admin.media.index') }}" class="btn-secondary flex-1 text-center min-h-[48px] flex items-center justify-center text-base font-semibold">
                    ❌ Cancel
                </a>
            </div>

            <div class="p-4 bg-gray-800 rounded-lg border border-gray-700">
                <p class="text-gray-300 text-sm">
                    <strong>⚠️ Important:</strong> Large files may take a few minutes to upload. Keep this page open and don't refresh while uploading.
                </p>
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
        
        // Show size info
        const posterInfo = document.getElementById('posterInfo');
        document.getElementById('posterSize').textContent = formatBytes(file.size);
        posterInfo.classList.remove('hidden');
    }
});

// File selection handler
document.getElementById('file').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const fileInfo = document.getElementById('fileInfo');
        const fileName = document.getElementById('fileName');
        const fileSize = document.getElementById('fileSize');
        const maxSize = 2 * 1024 * 1024 * 1024; // 2GB

        fileName.textContent = file.name;
        fileSize.textContent = formatBytes(file.size);

        if (file.size > maxSize) {
            alert('❌ File size exceeds 2GB limit!');
            e.target.value = '';
            fileInfo.classList.add('hidden');
            return;
        }

        fileInfo.classList.remove('hidden');
        const percent = Math.round((file.size / maxSize) * 100);
        document.getElementById('fileProgress').style.width = percent + '%';
        document.getElementById('filePercent').textContent = percent;
    }
});

// Form submission
document.getElementById('mediaForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    const submitSpinner = document.getElementById('submitSpinner');
    const form = this;
    
    // Validate file input
    const fileInput = document.getElementById('file');
    if (!fileInput.files.length) {
        alert('❌ Please select a media file to upload');
        return;
    }
    
    submitBtn.disabled = true;
    submitText.textContent = 'Starting upload...';
    submitSpinner.classList.remove('hidden');
    
    // Create FormData from the form
    const formData = new FormData(form);
    
    // Create XMLHttpRequest for progress tracking
    const xhr = new XMLHttpRequest();
    
    // Track upload progress
    xhr.upload.addEventListener('progress', function(e) {
        if (e.lengthComputable) {
            const percentComplete = Math.round((e.loaded / e.total) * 100);
            submitText.textContent = `Uploading... ${percentComplete}%`;
            console.log(`Upload progress: ${percentComplete}%`);
        }
    });
    
    // Handle completion
    xhr.addEventListener('load', function() {
        if (xhr.status >= 200 && xhr.status < 300) {
            // Success
            submitText.textContent = '✅ Upload complete! Redirecting...';
            setTimeout(() => {
                window.location.href = '{{ route("admin.media.index") }}';
            }, 1000);
        } else if (xhr.status === 302 || xhr.status === 301) {
            // Redirect
            submitText.textContent = '✅ Upload complete! Redirecting...';
            setTimeout(() => {
                window.location.href = '{{ route("admin.media.index") }}';
            }, 1000);
        } else {
            // Error
            submitText.textContent = '❌ Upload failed. Try again.';
            submitBtn.disabled = false;
            submitSpinner.classList.add('hidden');
            const response = xhr.responseText;
            try {
                const error = JSON.parse(response);
                alert('❌ Upload error: ' + (error.message || 'Unknown error'));
            } catch(e) {
                alert('❌ Upload failed with status: ' + xhr.status);
            }
        }
    });
    
    // Handle errors
    xhr.addEventListener('error', function() {
        submitText.textContent = '❌ Upload error. Try again.';
        submitBtn.disabled = false;
        submitSpinner.classList.add('hidden');
        alert('❌ Network error during upload');
    });
    
    // Handle abort
    xhr.addEventListener('abort', function() {
        submitText.textContent = '⏹️ Upload cancelled.';
        submitBtn.disabled = false;
        submitSpinner.classList.add('hidden');
    });
    
    // Send the request
    xhr.open('POST', form.action, true);
    xhr.send(formData);
});

function formatBytes(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return Math.round((bytes / Math.pow(k, i)) * 100) / 100 + ' ' + sizes[i];
}
</script>
@endsection

@endsection
