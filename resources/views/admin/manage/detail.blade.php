@extends('layouts.admin')

@section('content')
    <div class="container mx-auto mt-[100px]">
        <div class="bg-white p-4 md:p-6 rounded-2xl shadow-lg border border-gray-200 max-w-3xl mx-auto">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-700 mb-4 md:mb-6 flex items-center">
                📌 <span class="ml-2">Post Details</span>
            </h1>

            <div class="space-y-4">
                <p class="text-base md:text-lg font-semibold text-gray-600"><strong>Title:</strong> {{ $post->title }}</p>

                <div>
                    <p class="text-base md:text-lg font-semibold text-gray-600">Content:</p>
                    <div class="border p-4 md:p-5 rounded-lg bg-gray-50 shadow-inner text-gray-800 leading-relaxed">
                        {!! $post->content !!}
                    </div>
                </div>

                <div class="flex items-center space-x-3">
                    <p class="text-base md:text-lg font-semibold text-gray-600">Status:</p>
                    @if ($post->status == 'pending')
                        <span class="px-3 py-1 text-sm font-semibold bg-yellow-100 text-yellow-700 rounded-lg shadow">
                            Pending Approval
                        </span>
                    @elseif ($post->status == 'approved')
                        <span class="px-3 py-1 text-sm font-semibold bg-green-100 text-green-700 rounded-lg shadow">
                            Approved
                        </span>
                    @else
                        <span class="px-3 py-1 text-sm font-semibold bg-red-100 text-red-700 rounded-lg shadow">
                            Rejected
                        </span>
                    @endif
                </div>

                <p class="text-base md:text-lg font-semibold text-gray-600"><strong>Posted by:</strong>
                    {{ $post->user->name }}</p>
                <p class="text-base md:text-lg font-semibold text-gray-600"><strong>Created at:</strong>
                    {{ $post->created_at->format('d/m/Y H:i') }}</p>
                @if ($post->pdf_file)
                    <div class="mt-6">
                        <a href="{{ asset('storage/' . $post->pdf_file) }}" target="_blank"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            📄 View PDF
                        </a>
                    </div>
                @endif
            </div>

            <div class="mt-6 flex space-x-3 md:space-x-4 justify-center">
                @if ($post->status == 'pending')
                    <form action="{{ route('admin.posts.approve', $post->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                            class="confirm-action w-32 md:w-40 px-4 py-2 text-white bg-green-500 rounded-lg shadow-md hover:bg-green-600 hover:shadow-lg transition-all duration-300 transform hover:scale-105">
                            ✅ Approve
                        </button>
                    </form>

                    <form action="{{ route('admin.posts.reject', $post->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                            class="confirm-action w-32 md:w-40 px-4 py-2 text-white bg-red-500 rounded-lg shadow-md hover:bg-red-600 hover:shadow-lg transition-all duration-300 transform hover:scale-105">
                            ❌ Reject
                        </button>
                    </form>
                @elseif ($post->status == 'approved')
                    <form action="{{ route('admin.posts.reject', $post->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                            class="confirm-action w-32 md:w-40 px-4 py-2 text-white bg-red-500 rounded-lg shadow-md hover:bg-red-600 hover:shadow-lg transition-all duration-300 transform hover:scale-105">
                            ❌ Reject
                        </button>
                    </form>
                @elseif ($post->status == 'rejected')
                    <form action="{{ route('admin.posts.approve', $post->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                            class="confirm-action w-32 md:w-40 px-4 py-2 text-white bg-green-500 rounded-lg shadow-md hover:bg-green-600 hover:shadow-lg transition-all duration-300 transform hover:scale-105">
                            ✅ Approve
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.confirm-action').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    let form = this.closest("form"); // Get the form that the button belongs to

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "Do you really want to proceed with this action?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#4CAF50', // Changed color to green
                        cancelButtonColor: '#F44336', // Changed color to red
                        confirmButtonText: 'Yes, proceed!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit(); // If "Yes" is clicked, submit the form
                        }
                    });
                });
            });
        });
    </script>
@endsection
