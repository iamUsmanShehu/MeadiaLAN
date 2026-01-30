@extends('admin.layouts.app')

@section('title', 'Change Password')

@section('page-title', 'Change Password')

@section('content')
<div class="max-w-2xl">
    <div class="bg-gray-800 rounded-lg border border-gray-700 p-6">
        <h2 class="text-2xl font-bold mb-6">Change Password</h2>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-900 text-green-200 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.change-password') }}" method="post" class="space-y-6">
            @csrf

            <div>
                <label for="current_password" class="block text-sm font-medium mb-2">Current Password</label>
                <input 
                    type="password" 
                    id="current_password" 
                    name="current_password"
                    class="w-full px-4 py-2 bg-gray-700 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('current_password') ring-2 ring-red-500 @enderror"
                    required
                >
                @error('current_password')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium mb-2">New Password</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password"
                    class="w-full px-4 py-2 bg-gray-700 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password') ring-2 ring-red-500 @enderror"
                    required
                >
                @error('password')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium mb-2">Confirm Password</label>
                <input 
                    type="password" 
                    id="password_confirmation" 
                    name="password_confirmation"
                    class="w-full px-4 py-2 bg-gray-700 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required
                >
            </div>

            <div class="flex space-x-4">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 px-6 py-2 rounded-lg font-semibold">
                    Change Password
                </button>
                <a href="{{ route('admin.dashboard') }}" class="bg-gray-700 hover:bg-gray-600 px-6 py-2 rounded-lg font-semibold">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
