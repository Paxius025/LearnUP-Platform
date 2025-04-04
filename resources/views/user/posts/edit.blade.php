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
        }

        .ql-container {
            min-height: 330px !important;
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
            padding-top: 80px;
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
                <label for="title" class="block text-gray-700 text-lg font-semibold">Title</label>
                <input type="text" id="title" name="title" value="{{ old('title', $post->title) }}"
                    class="w-full p-3 border rounded-lg" required>
            </div>

            <div class="mb-3">
                <label for="image" class="block text-gray-700 text-lg font-semibold">Upload Cover Image</label>
            
                <div class="relative">
                    <input type="file" id="image" name="image" accept="image/*"
                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                        onchange="storeImagePreview()"/>
                    <div
                        class="w-full p-4 border border-gray-300 rounded-lg text-gray-500 text-center flex items-center justify-center bg-gray-50 hover:bg-gray-100 cursor-pointer">
                        <span id="image_label_text" class="text-sm font-medium">
                            @if ($post->image)
                                {{ $post->image }}
                            @else
                                No image selected
                            @endif
                        </span>
                    </div>
                </div>
            
                <!-- Show the preview button if the post has an image -->
                @if ($post->image)
                    <button id="previewButton"
                        class="mt-3 bg-blue-600 text-white px-4 py-2 rounded-lg shadow-md hover:bg-blue-700 transition duration-300 ease-in-out"
                        onclick="openImagePopup(event)">Preview Image</button>
            
                @else
                    <button id="previewButton"
                        class="mt-3 bg-blue-600 text-white px-4 py-2 rounded-lg shadow-md hover:bg-blue-700 transition duration-300 ease-in-out"
                        onclick="openImagePopup(event)">Preview Image</button>
                @endif
            </div>

             <div id="imageModal"
                class="fixed inset-0 bg-black bg-opacity-70 flex justify-center items-center hidden z-50">
                <div
                    class="bg-white p-6 rounded-lg shadow-2xl relative max-w-3xl max-h-[90vh] flex flex-col items-center">
                    <button onclick="closeImagePopup()"
                        class="absolute top-2 right-2 text-gray-700 text-2xl font-bold">&times;</button>
                        <img id="preview" src="{{ asset('storage/' . $post->image) }}" alt="Preview" class="max-w-full h-auto">
                </div>
            </div>

            <!-- 🔹 Content -->
            <div class="mb-3">
                <label class="block text-gray-700 text-lg font-semibold">Content</label>
                <div id="editor">{!! old('content', $post->content) !!}</div>
                <input type="hidden" name="content" id="content">
            </div>

            <!-- 🔹 PDF Upload -->
            <div class="mb-3">
                <label for="pdf_file" class="block text-gray-700 text-lg font-semibold">Replace PDF (Optional)</label>
                <input type="file" id="pdf_file" name="pdf_file" accept="application/pdf"
                    class="w-full p-3 border rounded-lg">

                @if ($post->pdf_file)
                    <p class="mt-2 pt-2">
                        <a href="{{ asset('storage/' . $post->pdf_file) }}" target="_blank"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            View PDF
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
        function storeImagePreview() {
            const fileInput = document.getElementById('image');
            const previewButton = document.getElementById('previewButton');
            const previewImage = document.getElementById('preview');
            const labelText = document.getElementById('image_label_text');

            if (fileInput.files && fileInput.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewButton.classList.remove('hidden');  // Show the preview button
                };
                reader.readAsDataURL(fileInput.files[0]);

                labelText.textContent = fileInput.files[0].name;
            }
        }
        function openImagePopup() {
            event.preventDefault();
            document.getElementById('imageModal').classList.remove('hidden');
        }

        function closeImagePopup() {
            event.preventDefault();
            document.getElementById('imageModal').classList.add('hidden');
        }

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
                const maxSize = 20 * 1024 * 1024; // 20MB
                if (file.size > maxSize) {
                    Swal.fire({
                        icon: 'error',
                        title: 'File to large!',
                        text: 'Please select a file that is no larger than 20MB.',
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
                
                // ตรวจสอบ role ของผู้ใช้
                let userRole = "{{ Auth::user()->role }}";  // ตัวอย่างการดึง role จาก session

                if (userRole === 'writer') {
                    // ถ้า role เป็น writer
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "Are you sure you want to edit this post?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#4CAF50',
                        cancelButtonColor: '#F44336',
                        confirmButtonText: 'Yes, proceed!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit(); // ถ้า "Yes" คลิกให้ส่งฟอร์ม
                        }
                    });
                } else {
                    // หาก role ไม่ใช่ writer
                    Swal.fire({
                        title: 'Editing',
                        text: "The post will revert its status to Pending Approval.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#4CAF50', 
                        cancelButtonColor: '#F44336',
                        confirmButtonText: 'Yes, proceed!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit(); // ถ้า "Yes" คลิกให้ส่งฟอร์ม
                        }
                    });
                }
            });
        });
    });
    </script>

</body>

</html>
