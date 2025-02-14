@extends('layouts.admin')

@section('content')
<div class="container mx-auto p-6 pt-[120px]">
    <h2 class="text-2xl font-semibold mb-4">Create New User</h2>

    @if(session('success'))
        <div class="bg-green-500 text-white p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium">Name</label>
            <input type="text" name="name" id="name" class="w-full border p-2 rounded" required>
        </div>

        <div class="mb-4">
            <label for="email" class="block text-sm font-medium">Email</label>
            <input type="email" name="email" id="email" class="w-full border p-2 rounded" required>
        </div>

        <div class="mb-4">
            <label for="password" class="block text-sm font-medium">Password</label>
            <input type="password" name="password" id="password" class="w-full border p-2 rounded" required>
        </div>

        <div class="mb-4">
            <label for="password_confirmation" class="block text-sm font-medium">Confirm Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="w-full border p-2 rounded" required>
        </div>

        <div class="mb-4">
            <label for="role" class="block text-sm font-medium">Role</label>
            <select name="role" id="role" class="w-full border p-2 rounded">
                <option value="user">User</option>
                <option value="writer">Writer</option>
                <option value="admin">Admin</option>
            </select>
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Create User</button>
    </form>
</div>
@endsection
