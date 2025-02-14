@extends('layouts.admin')

@section('content')
    <div class="container mx-auto p-6 pt-[150px]">

        <div class="max-w-3xl mx-auto bg-white shadow-lg rounded-lg p-8">
            <h2 class="text-2xl font-semibold mb-6 text-green-700 flex items-center">
                ✏️ Edit User Role
            </h2>

            @if (session('success'))
                <div class="bg-green-500 text-white p-3 rounded mb-4 shadow-md">
                    {{ session('success') }}
                </div>
            @endif

            <form id="editUserForm" action="{{ route('admin.users.update', $user->id) }}" method="POST">
                @csrf
                @method('PATCH')

                <!-- Name -->
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" id="name" name="name" value="{{ $user->name }}"
                        class="w-full border border-gray-300 p-3 rounded-lg bg-gray-100 text-gray-500 cursor-not-allowed shadow-inner"
                        readonly>
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="email" name="email" value="{{ $user->email }}"
                        class="w-full border border-gray-300 p-3 rounded-lg bg-gray-100 text-gray-500 cursor-not-allowed shadow-inner"
                        readonly>
                </div>

                <!-- Role -->
                <div class="mb-6">
                    <label for="role" class="block text-sm font-medium text-gray-700">Change Role</label>
                    <select id="role" name="role"
                        class="w-full border border-gray-300 p-3 rounded-lg bg-white focus:ring-2 focus:ring-green-500 focus:outline-none transition-all duration-200 shadow-md">
                        <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User</option>
                        <option value="writer" {{ $user->role === 'writer' ? 'selected' : '' }}>Writer</option>
                        <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button type="submit" id="submitEdit"
                        class="bg-green-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-green-600 transition-all duration-200 transform hover:scale-105">
                        ✅ Update Role
                    </button>
                </div>
            </form>

            <!-- Back Button -->
            <div class="mt-6 flex justify-center">
                <a href="{{ route('admin.users') }}"
                    class="px-5 py-3 bg-gray-100 text-green-700 rounded-lg shadow-md hover:bg-gray-200 transition-all duration-300 flex items-center">
                    ← Back to Users
                </a>
            </div>
        </div>
    </div>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.getElementById('submitEdit').addEventListener('click', function(event) {
            event.preventDefault();

            Swal.fire({
                title: "Are you sure?",
                text: "Do you want to update this user's role?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#38a169",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, update it!",
                cancelButtonText: "Cancel"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('editUserForm').submit();
                }
            });
        });
    </script>
@endsection
