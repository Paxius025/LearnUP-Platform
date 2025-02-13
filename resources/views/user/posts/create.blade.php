<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LearnUP</title>
    @vite('resources/css/app.css')
    <link rel="icon" href="{{ asset('bookshelf.ico') }}" type="image/x-icon">
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <!-- Cropper.js CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" rel="stylesheet">
    <!-- Cropper.js JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
    <style>
        #editor {
            min-height: 150px;
            /* เพิ่มขนาดขั้นต่ำของ Editor */
            height: 300px;
            /* เพิ่มความสูงของ Editor */
            max-height: 500px;
            /* เพิ่มความสูงสูงสุด */
        }

        .ql-container {
            min-height: 150px !important;
            height: 300px !important;
            max-height: 400px !important;
            /* ปรับให้พอดีกับ Layout */
            overflow: hidden !important;
            /* ป้องกันล้น */
        }

        .ql-editor {
            min-height: 150px !important;
            height: 300px !important;
            max-height: 400px !important;
            padding: 10px !important;
            overflow-y: auto !important;
            /* ให้ Scroll ถ้ามีเนื้อหาเยอะ */
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen font-sans antialiased">
    @include('components.navbar')
    <div class="max-w-4xl mx-auto mt-5 bg-white p-8 rounded-xl shadow-xl">
        <h2 class="text-3xl font-bold text-gray-800 mb-6">Create New Post</h2>

        <form action="{{ route('user.posts.store') }}" method="POST" enctype="multipart/form-data"
            onsubmit="return validateForm()">
            @csrf
            <div class="mb-3">
                <label for="title" class="block text-gray-700 text-lg font-semibold">Title</label>
                <input type="text" id="title" name="title"
                    class="w-full p-4 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    required>
            </div>

            <div class="mb-3">
                <label class="block text-gray-700 text-lg font-semibold">Content</label>
                <div id="editor" class="bg-white border border-gray-300 rounded-lg p-4 min-h-[500px] shadow-md">
                </div>
                <input type="hidden" name="content" id="content" required>
                <div id="content-error" class="text-red-500 text-sm mt-2 hidden">Please fill out this field</div>
            </div>

            <div class="mb-3">
                <label for="pdf_file" class="block text-gray-700 text-lg font-semibold">Upload PDF (Optional)</label>
                <input type="file" id="pdf_file" name="pdf_file" accept="application/pdf"
                    class="w-full p-4 border border-gray-300 rounded-lg shadow-sm">
            </div>

            <div class="flex justify-center">
                <button type="submit"
                    class="bg-blue-600 text-white px-8 py-4 rounded-lg shadow-md hover:bg-blue-700 transition duration-300 ease-in-out transform hover:scale-105">Publish</button>
            </div>
        </form>
    </div>

    <script>
        var quill = new Quill('#editor', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{
                        'header': [1, 2, false]
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

        quill.on('text-change', function() {
            document.getElementById('content').value = quill.root.innerHTML;
        });

        // Handle Image Upload with Crop
        quill.getModule('toolbar').addHandler('image', function() {
            var input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/*');
            input.click();

            input.onchange = async () => {
                var file = input.files[0];
                if (file) {
                    showCropper(file);
                }
            };
        });

        function showCropper(file) {
            var reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = function(event) {
                var image = document.createElement('img');
                image.src = event.target.result;
                image.id = 'cropper-image';
                image.style.maxWidth = '100%';

                var modal = document.createElement('div');
                modal.id = 'cropper-modal';
                modal.style.position = 'fixed';
                modal.style.top = '0';
                modal.style.left = '0';
                modal.style.width = '100vw';
                modal.style.height = '100vh';
                modal.style.background = 'rgba(0, 0, 0, 0.7)';
                modal.style.display = 'flex';
                modal.style.alignItems = 'center';
                modal.style.justifyContent = 'center';
                modal.innerHTML = `
                    <div style="background: white; padding: 20px; border-radius: 10px; text-align: center;">
                        <h2>Crop Image</h2>
                        <div id="crop-container" style="max-width: 500px; max-height: 400px; overflow: hidden;">
                        </div>
                        <button id="crop-btn" class="bg-green-500 text-white px-6 py-2 rounded-md mt-4">Crop & Upload</button>
                        <button id="cancel-btn" class="bg-red-500 text-white px-6 py-2 rounded-md mt-4">Cancel</button>
                    </div>
                `;
                document.body.appendChild(modal);
                document.getElementById('crop-container').appendChild(image);

                var cropper = new Cropper(image, {
                    aspectRatio: 16 / 9, // Adjust as needed (1:1, 4:3, etc.)
                    viewMode: 2,
                });

                document.getElementById('crop-btn').onclick = async function() {
                    var canvas = cropper.getCroppedCanvas();
                    canvas.toBlob(async function(blob) {
                        var formData = new FormData();
                        formData.append("image", blob, "cropped_" + file.name);

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

                        document.body.removeChild(modal);
                    }, 'image/jpeg', 0.8); // Quality 80%
                };

                document.getElementById('cancel-btn').onclick = function() {
                    document.body.removeChild(modal);
                };
            };
        }

        function validateForm() {
            var content = document.getElementById('content').value;
            if (!content.trim()) {
                document.getElementById('content-error').classList.remove('hidden');
                return false;
            }
            return true;
        }
    </script>
</body>

</html>
