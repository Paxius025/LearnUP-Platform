<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Post</title>
    @vite('resources/css/app.css')
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <!-- Cropper.js CSS -->
    <link  href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" rel="stylesheet">
    <!-- Cropper.js JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>

</head>
<body class="bg-gray-100 min-h-screen">
    @include('components.navbar')
    <div class="max-w-4xl mx-auto mt-10 bg-white p-6 rounded shadow">
        <h2 class="text-2xl font-bold">Create New Post</h2>

        <form action="{{ route('user.posts.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label for="title" class="block text-gray-700">Title</label>
                <input type="text" id="title" name="title" class="w-full p-3 border rounded-lg" required>
            </div>
        
            <div class="mb-4">
                <label class="block text-gray-700">Content</label>
                <div id="editor"></div>
                <input type="hidden" name="content" id="content">
            </div>
        
            <div class="mb-4">
                <label for="pdf_file" class="block text-gray-700">Upload PDF (Optional)</label>
                <input type="file" id="pdf_file" name="pdf_file" class="w-full p-3 border rounded-lg">
            </div>
        
            <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg">Publish</button>
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
                        <button id="crop-btn" style="margin-top: 10px; padding: 10px 20px; background: green; color: white; border: none; cursor: pointer;">Crop & Upload</button>
                        <button id="cancel-btn" style="margin-top: 10px; padding: 10px 20px; background: red; color: white; border: none; cursor: pointer;">Cancel</button>
                    </div>
                `;
                document.body.appendChild(modal);
                document.getElementById('crop-container').appendChild(image);
    
                var cropper = new Cropper(image, {
                    aspectRatio: 16 / 9, // ปรับเป็น 1:1, 4:3 หรืออื่น ๆ ตามต้องการ
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
                    }, 'image/jpeg', 0.8); // คุณภาพ 80%
                };
    
                document.getElementById('cancel-btn').onclick = function() {
                    document.body.removeChild(modal);
                };
            };
        }
    </script>
    
    

</body>
</html>
