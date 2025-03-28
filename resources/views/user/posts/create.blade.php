<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="Sat, 01 Jan 2000 00:00:00 GMT">
    <title>LearnUP</title>
    @vite('resources/css/app.css')
    <link rel="icon" href="{{ asset('bookshelf.ico') }}" type="image/x-icon">
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <style>
        #editor {
            min-height: 150px;
            height: auto;
        }

        .ql-container {
            min-height: 350px !important;
            height: auto !important;
            max-height: none !important;
            overflow-y: hidden !important;
        }

        .ql-editor {
            min-height: 150px !important;
            height: auto !important;
            max-height: none !important;
            padding: 10px !important;
            overflow-y: hidden !important;
        }

        body {
            padding-top: 80px;
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen font-sans antialiased">
    @include('components.navbar')
    <div class="max-w-5xl mx-auto mt-5 bg-white p-6 rounded-xl border border-gray-200">
        <form action="{{ route('user.posts.store') }}" method="POST" enctype="multipart/form-data" id="postForm">
            @csrf
            <div class="mb-3">
                <label for="title" class="block text-gray-700 text-lg font-semibold">Title</label>
                <input type="text" id="title" name="title"
                    class="w-full p-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    placeholder="Please fill out this field" required>
            </div>

            <div class="mb-3">
                <label for="image" class="block text-gray-700 text-lg font-semibold mb-2">Upload Cover Image</label>

                <div class="relative">
                    <input type="file" id="image" name="image" accept="image/*"
                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                        onchange="storeImagePreview()" />
                    <div
                        class="w-full p-4 border border-gray-300 rounded-lg text-gray-500 text-center flex items-center justify-center bg-gray-50 hover:bg-gray-100 cursor-pointer">
                        <span id="image_label_text" class="text-sm font-medium">Choose an image</span>
                    </div>
                </div>

                <button id="previewButton"
                    class="mt-3 bg-blue-600 text-white px-4 py-2 rounded-lg shadow-md hover:bg-blue-700 transition duration-300 ease-in-out hidden"
                    onclick="openImagePopup()">Preview Image</button>
            </div>

            <!-- Pop-up Modal -->
            <div id="imageModal"
                class="fixed inset-0 bg-black bg-opacity-70 flex justify-center items-center hidden z-50">
                <div
                    class="bg-white p-6 rounded-lg shadow-2xl relative max-w-3xl max-h-[90vh] flex flex-col items-center">
                    <button onclick="closeImagePopup()"
                        class="absolute top-2 right-2 text-gray-700 text-2xl font-bold">&times;</button>
                    <img id="preview" src="" alt="Image Preview" class="max-w-full max-h-[80vh] rounded-lg">
                </div>
            </div>
            <div class="mb-3">
                <label class="block text-gray-700 text-lg font-semibold">Content</label>
                <div id="editor" class="bg-white border border-gray-200 rounded-lg p-4 min-h-[500px]"></div>
                <input type="hidden" name="content" id="content" required>
            </div>

            <div class="mb-3">
                <label for="pdf_file" class="block text-gray-700 text-lg font-semibold mb-2">
                    Upload PDF (Optional)
                </label>

                <div class="relative">
                    <input type="file" id="pdf_file" name="pdf_file" accept="application/pdf"
                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                        onchange="showFileName()" />
                    <div
                        class="w-full p-4 border border-gray-300 rounded-lg text-gray-500 text-center flex items-center justify-center bg-gray-50 hover:bg-gray-100 cursor-pointer">
                        <span id="pdf_label_text" class="text-sm font-medium">Choose a PDF file</span>
                    </div>
                </div>
            </div>

            <div class="flex justify-center">
                <button type="submit" id="publishBtn"
                    class="bg-blue-600 text-white px-8 py-4 rounded-lg shadow-md hover:bg-blue-700 transition duration-300 ease-in-out transform hover:scale-105 disabled:bg-gray-400 disabled:cursor-not-allowed"
                    disabled>Publish</button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function storeImagePreview() {
            const fileInput = document.getElementById('image');
            const previewButton = document.getElementById('previewButton');
            const previewImage = document.getElementById('preview');

            if (fileInput.files && fileInput.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewButton.classList.remove('hidden'); // แสดงปุ่ม Preview
                };
                reader.readAsDataURL(fileInput.files[0]);
            }
        }

        function openImagePopup() {
            document.getElementById('imageModal').classList.remove('hidden');
        }

        function closeImagePopup() {
            document.getElementById('imageModal').classList.add('hidden');
        }

        function showFileName() {
            const input = document.getElementById('pdf_file');
            const label = document.getElementById('pdf_label_text');
            const file = input.files[0];

            if (file) {
                label.textContent = `${file.name}`;
            } else {
                label.textContent = 'Choose a PDF file';
            }
        }

        document.addEventListener("DOMContentLoaded", function() {
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
                },
                placeholder: 'Please fill out this field'
            });

            const titleInput = document.getElementById('title');
            const contentInput = document.getElementById('content');
            const publishBtn = document.getElementById('publishBtn');

            // Set initial empty content
            contentInput.value = '';

            // Function to check form validity
            function checkFormValidity() {
                const titleValue = titleInput.value.trim();
                const contentValue = contentInput.value.trim();

                const isTitleValid = titleValue.length > 0;
                const isContentValid = contentValue && contentValue !== '<p><br></p>' && contentValue !== '<p></p>';

                publishBtn.disabled = !(isTitleValid && isContentValid);
            }

            // Initial content setup
            quill.on('text-change', function() {
                contentInput.value = quill.root.innerHTML.trim();
                checkFormValidity();
            });

            // Title input listener
            titleInput.addEventListener('input', checkFormValidity);

            // Form submission
            document.getElementById('postForm').addEventListener('submit', function(event) {
                const contentValue = contentInput.value.trim();
                if (!titleInput.value.trim() || !contentValue || contentValue === '<p><br></p>') {
                    event.preventDefault();
                    checkFormValidity();
                }
            });

            // Handle Image Upload
            quill.getModule('toolbar').addHandler('image', function() {
                var input = document.createElement('input');
                input.setAttribute('type', 'file');
                input.setAttribute('accept', 'image/*');
                input.click();

                input.onchange = async () => {
                    var file = input.files[0];
                    if (file) {
                        var formData = new FormData();
                        formData.append("image", file);

                        const res = await fetch("{{ route('posts.upload.image') }}", {
                            method: "POST",
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': "{{ csrf_token() }}"
                            }
                        });

                        const data = await res.json();
                        if (data.url) {
                            var range = quill.getSelection();
                            quill.insertEmbed(range.index, 'image', data.url);
                        }
                    }
                };
            });
            document.getElementById('pdf_file').addEventListener('change', function(event) {
                const file = event.target.files[0];
                if (file) {
                    const maxSize = 20 * 1024 * 1024;
                    if (file.size > maxSize) {
                        Swal.fire({
                            icon: 'error',
                            title: 'File too large!',
                            text: 'Please select a file that is no larger than 20MB.',
                        });
                        event.target.value = '';
                    }
                }
            });

            // Initial validation check
            checkFormValidity();
        });
        // PDF file size validation 
    </script>
</body>

</html>
