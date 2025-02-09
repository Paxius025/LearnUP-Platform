<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post</title>
    @vite('resources/css/app.css')
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen">

    <div class="max-w-4xl mx-auto mt-10 bg-white p-6 rounded shadow">
        <h2 class="text-2xl font-bold">Edit Post</h2>

        <form action="{{ route('user.posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label for="title" class="block text-gray-700">Title</label>
                <input type="text" id="title" name="title" value="{{ $post->title }}" class="w-full p-3 border rounded-lg" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Content</label>
                <div id="editor">{!! $post->content !!}</div>
                <input type="hidden" name="content" id="content">
            </div>

            <div class="mb-4">
                <label for="pdf_file" class="block text-gray-700">Replace PDF (Optional)</label>
                <input type="file" id="pdf_file" name="pdf_file" class="w-full p-3 border rounded-lg">
                @if ($post->pdf_file)
                    <p class="mt-2">
                        Current PDF: 
                        <a href="{{ asset('storage/' . $post->pdf_file) }}" target="_blank" class="text-blue-600 hover:underline">
                            📄 View PDF
                        </a>
                    </p>
                @endif
            </div>

            <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg">Update Post</button>
        </form>
    </div>

    <script>
        var quill = new Quill('#editor', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, false] }],
                    ['bold', 'italic', 'underline'],
                    ['image', 'link'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }]
                ]
            }
        });

        // กำหนดค่าจากโพสต์เดิม
        quill.root.innerHTML = {!! json_encode($post->content) !!};

        quill.on('text-change', function() {
            document.getElementById('content').value = quill.root.innerHTML;
        });
    </script>

</body>
</html>
