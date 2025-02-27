<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LearnUP</title>
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="Sat, 01 Jan 2000 00:00:00 GMT">
    @vite('resources/css/app.css')
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <link rel="icon" href="{{ asset('bookshelf.ico') }}" type="image/x-icon">
    <style>
        #editor {
            min-height: 150px;
            height: auto;
            /* ปรับความสูงอัตโนมัติ */
        }

        .ql-container {
            min-height: 400px !important;
            height: auto !important;
            max-height: none !important;
            overflow-y: hidden !important;
            /* ปิด Scroll */
        }

        .ql-editor {
            min-height: 150px !important;
            height: auto !important;
            max-height: none !important;
            padding: 10px !important;
            overflow-y: hidden !important;
            /* ปิด Scroll */
        }


        body {
            padding-top: 100px;
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen">
    @include('components.navbar')

    <div class="max-w-5xl mx-auto bg-white p-6 rounded shadow">

        <!-- ⚠️ แสดงข้อความเตือนถ้าโพสต์เคยได้รับการอนุมัติ -->
        @if (auth()->check() && auth()->user()->role === 'user')
            <div class="bg-yellow-200 text-yellow-800 p-3 rounded-lg mb-4">
                ⚠️ Editing the post will revert its status to <strong>"Pending Approval"</strong>
            </div>
        @endif

        <form action="{{ route('user.posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- 🔹 Title -->
            <div class="mb-3">
                <label for="title" class="block text-gray-700">Title</label>
                <input type="text" id="title" name="title" value="{{ old('title', $post->title) }}"
                    class="w-full p-3 border rounded-lg" required>
            </div>

            <!-- 🔹 Content -->
            <div class="mb-3">
                <label class="block text-gray-700">Content</label>
                <div id="editor">{!! old('content', $post->content) !!}</div>
                <input type="hidden" name="content" id="content">
            </div>

            <!-- 🔹 PDF Upload -->
            <div class="mb-3">
                <label for="pdf_file" class="block text-gray-700">Replace PDF (Optional)</label>
                <input type="file" id="pdf_file" name="pdf_file" accept="application/pdf"
                    class="w-full p-3 border rounded-lg">

                @if ($post->pdf_file)
                    <p class="mt-2 pt-2">
                        <a href="{{ asset('storage/' . $post->pdf_file) }}" target="_blank"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            📄 View PDF
                        </a>
                    </p>
                @endif
            </div>


            <!-- 🔹 Update Button (Centered) -->
            <div class="flex justify-center mt-4">
                <button type="submit"
                    class="confirm-action bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">
                    Update Post
                </button>
            </div>

        </form>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- ✅ Quill.js Script -->
    <script>
        var quill = new Quill('#editor', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{
                        'header': [1, 2, false]
                    }],
                    [{
                        'size': ['small', 'normal', 'large']
                    }],
                    ['bold', 'italic', 'underline'],
                    ['image', 'link'],
                    [{
                        'list': 'ordered'
                    }, {
                        'list': 'bullet'
                    }]
                ]
            }
        });
        quill.root.innerHTML = {!! json_encode($post->content) !!};

        quill.on('text-change', function() {
            document.getElementById('content').value = quill.root.innerHTML;
        });

        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById('content').value = quill.root.innerHTML;
        });

        document.getElementById('pdf_file').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const maxSize = 10 * 1024 * 1024; // 10MB
                if (file.size > maxSize) {
                    Swal.fire({
                        icon: 'error',
                        title: 'File to large!',
                        text: 'Please select a file that is no larger than 10MB.',
                    });
                    event.target.value = ''; // Reset the input
                }
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.confirm-action').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    let form = this.closest("form"); // Get the form that the button belongs to

                    Swal.fire({
                        title: 'Editing',
                        text: "the post will revert its status to Pending Approval",
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

</body>

</html>
