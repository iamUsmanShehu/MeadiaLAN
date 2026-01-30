@extends('layouts.app')

@section('title', 'Admin Login')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-8">
    <div class="w-full max-w-md">
        <div class="bg-gray-800 rounded-lg p-6 sm:p-8 border border-gray-700">
            <h1 class="text-2xl sm:text-3xl font-bold text-center mb-8 text-blue-500">MediaLAN Admin</h1>

            @if($errors->any())
                <div class="mb-6 p-4 bg-red-900 text-red-200 rounded-lg border border-red-700">
                    <ul class="list-disc list-inside text-sm sm:text-base">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="post" action="{{ route('admin.login') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="username" class="block text-sm font-medium mb-2">Username</label>
                    <input 
                        type="text" 
                        id="username" 
                        name="username"
                        value="{{ old('username') }}"
                        class="w-full px-4 py-3 bg-gray-700 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 min-h-[48px] text-base"
                        required
                        autofocus
                    >
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium mb-2">Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password"
                        class="w-full px-4 py-3 bg-gray-700 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 min-h-[48px] text-base"
                        required
                    >
                </div>

                <button type="submit" class="btn-primary w-full">
                    Login
                </button>
            </form>

            <p class="text-gray-400 text-xs sm:text-sm text-center mt-6">
                Default credentials: admin / admin123
            </p>
        </div>
    </div>
</div>
@endsection
