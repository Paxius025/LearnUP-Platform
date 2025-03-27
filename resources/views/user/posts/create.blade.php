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
            min-height: 450px !important;
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
                <label class="block text-gray-700 text-lg font-semibold">Content</label>
                <div id="editor" class="bg-white border border-gray-200 rounded-lg p-4 min-h-[500px]"></div>
                <input type="hidden" name="content" id="content" required>
            </div>

            <div class="mb-3">
                <label for="pdf_file" class="block text-gray-700 text-lg font-semibold">Upload PDF (Optional)</label>
                <input type="file" id="pdf_file" name="pdf_file" accept="application/pdf"
                    class="w-full p-4 border border-gray-200 rounded-lg">
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

            // PDF file size validation
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
    </script>
</body>

</html>
