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
    <!-- Cropper.js CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" rel="stylesheet">
    <!-- Cropper.js JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
    <style>
        #editor {
            min-height: 150px;
            height: auto;
            /* ปรับความสูงอัตโนมัติ */
        }

        .ql-container {
            min-height: 450px !important;
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

<body class="bg-gray-100 min-h-screen font-sans antialiased">
    @include('components.navbar')
    <div class="max-w-5xl mx-auto mt-5 bg-white p-6 rounded-xl border border-gray-200">

        <form action="{{ route('user.posts.store') }}" method="POST" enctype="multipart/form-data"
            onsubmit="return validateForm()">
            @csrf
            <div class="mb-3">
                <label for="title" class="block text-gray-700 text-lg font-semibold">Title</label>
                <input type="text" id="title" name="title"
                    class="w-full p-4 border border-gray-300 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    required>
            </div>

            <div class="mb-3">
                <label class="block text-gray-700 text-lg font-semibold">Content</label>
                <div id="editor" class="bg-white border border-gray-200 rounded-lg p-4 min-h-[500px] ">
                </div>
                <input type="hidden" name="content" id="content" required>
                <div id="content-error" class="text-red-500 text-sm mt-2 hidden">Please fill out this field</div>
            </div>

            <div class="mb-3">
                <label for="pdf_file" class="block text-gray-700 text-lg font-semibold">Upload PDF (Optional)</label>
                <input type="file" id="pdf_file" name="pdf_file" accept="application/pdf"
                    class="w-full p-4 border border-gray-200 rounded-lg">
            </div>

            <div class="flex justify-center">
                <button type="submit"
                    class="bg-blue-600 text-white px-8 py-4 rounded-lg shadow-md hover:bg-blue-700 transition duration-300 ease-in-out transform hover:scale-105">Publish</button>
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
                    modal.style.zIndex = '1000';

                    modal.innerHTML = `
            <div style="background: white; padding: 20px; border-radius: 10px; text-align: center; max-width: 90%; max-height: 90%; overflow: auto;">
                <h2 class="text-lg font-semibold mb-2">Crop & Resize Image</h2>
                <select id="aspect-ratio" class="mb-2 p-2 border border-gray-300 rounded">
                    <option value="free">Free</option>
                    <option value="16/9">16:9</option>
                    <option value="4/3">4:3</option>
                    <option value="1/1">1:1</option>
                </select>
                <div id="crop-container" style="max-width: 500px; max-height: 400px; overflow: hidden; margin-bottom: 10px;"></div>
                <label>Width: <input type="number" id="resize-width" value="500" class="p-1 border border-gray-300 rounded w-20"></label>
                <label>Height: <input type="number" id="resize-height" value="300" class="p-1 border border-gray-300 rounded w-20"></label>
                <br>
                <button id="crop-btn" class="bg-green-500 text-white px-6 py-2 rounded-md mt-4">Crop & Upload</button>
                <button id="cancel-btn" class="bg-red-500 text-white px-6 py-2 rounded-md mt-4">Cancel</button>
            </div>
        `;

                    document.body.appendChild(modal);
                    document.getElementById('crop-container').appendChild(image);

                    var cropper = new Cropper(image, {
                        aspectRatio: NaN, // Default เป็น Free
                        viewMode: 2,
                        autoCropArea: 1,
                        movable: true,
                        zoomable: true,
                        scalable: true
                    });

                    // เปลี่ยน Aspect Ratio ตามที่ User เลือก
                    document.getElementById('aspect-ratio').addEventListener('change', function() {
                        let ratio = this.value === 'free' ? NaN : parseFloat(this.value);
                        cropper.setAspectRatio(ratio);
                    });

                    document.getElementById('crop-btn').onclick = async function() {
                        let resizeWidth = parseInt(document.getElementById('resize-width').value) ||
                            500;
                        let resizeHeight = parseInt(document.getElementById('resize-height').value) ||
                            300;

                        var canvas = cropper.getCroppedCanvas({
                            width: resizeWidth,
                            height: resizeHeight
                        });

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
                        }, 'image/jpeg', 0.8);
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
        });
    </script>

</body>

</html>
