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