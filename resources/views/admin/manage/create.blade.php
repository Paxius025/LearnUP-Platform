@extends('layouts.admin')

@section('content')
<div class="container mx-auto p-6 pt-[150px]">

    <div class="max-w-3xl mx-auto bg-white shadow-lg rounded-lg p-8">
        <h2 class="text-2xl font-semibold mb-6 text-green-700 flex items-center">
        Create New User
        </h2>

        @if(session('success'))
            <div class="bg-green-500 text-white p-3 rounded mb-4 shadow-md">
                {{ session('success') }}
            </div>
        @endif

        <form id="createUserForm" action="{{ route('admin.users.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" name="name" id="name"
                    class="w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none transition-all duration-200"
                    required>
            </div>

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email"
                    class="w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none transition-all duration-200"
                    required>
            </div>

            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" id="password"
                    class="w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none transition-all duration-200"
                    required>
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                    class="w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none transition-all duration-200"
                    required>
            </div>

            <div class="mb-6">
                <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                <select name="role" id="role"
                    class="w-full border border-gray-300 p-3 rounded-lg bg-white focus:ring-2 focus:ring-green-500 focus:outline-none transition-all duration-200">
                    <option value="user">User</option>
                    <option value="writer">Writer</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

            <div class="flex justify-end">
                <button type="submit" id="submitButton"
                    class="bg-green-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-green-600 transition-all duration-200 transform hover:scale-105">
                    ✅ Create User
                </button>
            </div>
        </form>
    </div>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.getElementById('submitButton').addEventListener('click', function(event) {
        event.preventDefault(); 

        Swal.fire({
            title: "Are you sure?",
            text: "Do you want to create this user?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#38a169",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, create it!",
            cancelButtonText: "Cancel"
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('createUserForm').submit();
            }
        });
    });
</script>

@endsection
