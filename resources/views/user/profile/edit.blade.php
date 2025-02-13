@extends('layouts.app')

@section('content')
    <div class="fixed top-0 left-0 w-full bg-white shadow-md z-50">
        @include('components.navbar')
    </div>

    <div class="flex justify-center items-center mt-2 py-10">
        <div class="w-full max-w-lg bg-white p-6 rounded-lg shadow-lg relative">

            <!-- 🏷️ Role Badge -->
            <div class="absolute top-0 right-0 mt-4 mr-4">
                <span
                    class="px-3 py-1 text-xs font-bold text-white rounded-full
                    {{ $user->role === 'admin' ? 'bg-green-500' : ($user->role === 'writer' ? 'bg-blue-500' : 'bg-gray-500') }}">
                    {{ ucfirst($user->role) }}
                </span>
            </div>

            <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Edit Profile</h2>

            <!-- SweetAlert2 for success notification -->
            @if (session('success'))
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Profile Updated!',
                        text: "{{ session('success') }}",
                        timer: 3000, // Auto close after 3 seconds
                        showConfirmButton: false
                    });
                </script>
            @endif

            <!-- Avatar Section -->
            <div class="flex justify-center mb-6 relative">
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
                    <textarea name="bio" rows="4"
                        class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-green-400 h-25 resize-none">{{ old('bio', $user->bio) }}</textarea>
                    @error('bio')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Profile Picture -->
                <div class="mb-4">
                    <label class="block font-semibold text-gray-700">Upload picture</label>
                    <input type="file" name="avatar" id="avatarInput"
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
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Check file size not to exceed 10MB
        document.getElementById('avatarInput').addEventListener('change', function() {
            var file = this.files[0];
            if (file && file.size > 10 * 1024 * 1024) { // 10MB
                Swal.fire({
                    icon: 'error',
                    title: 'File too large!',
                    text: 'The uploaded image must be less than 10MB.',
                });
                this.value = ''; // Clear file input
            }
        });
    </script>
@endsection