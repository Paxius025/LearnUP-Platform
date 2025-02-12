@extends('layouts.app')

@section('content')
    <div class="fixed top-0 left-0 w-full bg-white shadow-md z-50">
        @include('components.navbar')
    </div>

    <div class="flex justify-center items-center mt-2  py-10">
        <div class="w-full max-w-lg bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Edit Profile</h2>

            @if (session('success'))
                <div class="bg-green-100 text-green-700 p-3 rounded-md text-center mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Avatar Section -->
            <div class="flex justify-center mb-6">
                <div class="relative w-32 h-32">
                    @if ($user->avatar)
                        <img src="{{ asset('storage/avatars/' . $user->avatar) }}" alt="Avatar"
                            class="w-32 h-32 rounded-full border-4 border-green-400 shadow-md object-cover">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=128&background=10B981&color=fff"
                            class="w-32 h-32 rounded-full border-4 border-gray-300 shadow-md">
                    @endif
                </div>
            </div>

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Name -->
                <div class="mb-4">
                    <label class="block font-semibold text-gray-700">Username</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                        class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-green-400">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Bio -->
                <div class="mb-4">
                    <label class="block font-semibold text-gray-700">Bio</label>
                    <textarea name="bio" rows="4" class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-green-400 h-25 resize-none">{{ old('bio', $user->bio) }}</textarea>
                    @error('bio')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Profile Picture -->
                <div class="mb-4">
                    <label class="block font-semibold text-gray-700">Upload picture</label>
                    <input type="file" name="avatar"
                        class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-green-400">
                    @error('avatar')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Buttons -->
                <div class="flex justify-between mt-6">
                    @if (auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}"
                            class="bg-gray-500 text-white px-4 py-2 rounded-lg shadow-md hover:bg-gray-600 transition font-semibold">
                            Cancel
                        </a>
                    @else
                        <a href="{{ route('user.dashboard') }}"
                            class="bg-gray-500 text-white px-4 py-2 rounded-lg shadow-md hover:bg-gray-600 transition font-semibold">
                            Cancel
                        </a>
                    @endif


                    <button type="submit"
                        class="bg-green-500 text-white px-6 py-2 rounded-lg shadow-md hover:bg-green-600 transition font-semibold">
                        save change
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
