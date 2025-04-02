<table class="table-auto w-full bg-white border border-gray-300 rounded-lg shadow-md">
    <thead class="bg-gray-100">
        <tr>
            <th class="px-6 py-3 text-center text-sm font-bold text-black min-w-[250px]">Title</th>
            <th class="px-6 py-3 text-center text-sm font-bold text-black min-w-[270px]">Content</th>
            <th class="px-6 py-3 text-center text-sm font-bold text-black min-w-[150px]">Date</th>
            <th class="px-6 py-3 text-center text-sm font-bold text-black min-w-[100px]">Status</th>
            <th class="px-6 py-3 text-center text-sm font-bold text-black min-w-[200px]">Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($posts as $post)
            <tr class="border-t border-gray-200">
                <td class="px-6 py-4 text-left">
                    <a href="{{ route('user.posts.show', $post->id) }}" class="text-blue-600 hover:underline">
                        {{ Str::limit($post->title, 35) }}
                    </a>
                </td>
                <td class="px-6 py-4 text-center text-gray-700">
                    {!! Str::limit(strip_tags($post->content), 20) !!}
                </td>
                <td class="px-6 py-4 text-center text-gray-500 text-sm">
                    {{ $post->created_at->format('M d, Y') }}
                </td>
                <td class="px-6 py-4 text-center">
                    @if ($post->status === 'approved')
                        <span class="text-green-600 font-bold">Approved</span>
                    @elseif ($post->status === 'pending')
                        <span class="text-yellow-600 font-bold">Pending</span>
                    @else
                        <span class="text-red-600 font-bold">Rejected</span>
                    @endif
                </td>
                <td class="px-6 py-4 text-center">
                    <div class="flex justify-center items-center space-x-2">
                        <a href="{{ route('user.posts.edit', $post->id) }}"
                            class="px-4 py-2 w-20 bg-yellow-500 text-white rounded-md shadow-md hover:bg-yellow-600 transition duration-300 transform hover:scale-105 flex items-center justify-center gap-1">
                            Edit
                        </a>

                        <form action="{{ route('user.posts.delete', $post->id) }}" method="POST" class="m-0">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="confirm-action px-4 py-2 w-20 bg-red-600 text-white rounded-md shadow-md hover:bg-red-700 transition duration-300 transform hover:scale-105 flex items-center justify-center gap-1">
                                Delete
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="px-6 py-4 text-center text-gray-500">No posts found.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<div class="mt-4">
    {{ $posts->appends(request()->query())->links() }}
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.confirm-action').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                let form = this.closest("form"); // Get the form that the button belongs to

                Swal.fire({
                    title: 'Delete Post',
                    text: "Are you sure you want to delete this post?",
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
