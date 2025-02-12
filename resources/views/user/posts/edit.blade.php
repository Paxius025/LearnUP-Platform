<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LearnUP</title>
    @vite('resources/css/app.css')
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
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

<body class="bg-gray-100 min-h-screen">
    @include('components.navbar')

    <div class="max-w-4xl mx-auto mt-10 bg-white p-6 rounded shadow">
        <h2 class="text-2xl font-bold">Edit Post</h2>

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
            <div class="mb-4">
                <label for="title" class="block text-gray-700">Title</label>
                <input type="text" id="title" name="title" value="{{ old('title', $post->title) }}"
                    class="w-full p-3 border rounded-lg" required>
            </div>

            <!-- 🔹 Content -->
            <div class="mb-4">
                <label class="block text-gray-700">Content</label>
                <div id="editor">{!! old('content', $post->content) !!}</div>
                <input type="hidden" name="content" id="content">
            </div>

            <!-- 🔹 PDF Upload -->
            <div class="mb-4">
                <label for="pdf_file" class="block text-gray-700">Replace PDF (Optional)</label>
                <input type="file" id="pdf_file" name="pdf_file" class="w-full p-3 border rounded-lg">
                @if ($post->pdf_file)
                    <p class="mt-2">
                        Current PDF :
                        <a href="{{ asset('storage/' . $post->pdf_file) }}" target="_blank"
                            class="text-blue-600 hover:underline">
                            📄 View PDF
                        </a>
                    </p>
                @endif
            </div>

            <!-- 🔹 Update Button -->
            <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">
                Update Post
            </button>
        </form>
    </div>

    <!-- ✅ Quill.js Script -->
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

        // ✅ ตั้งค่าเริ่มต้นของ Quill.js
        quill.root.innerHTML = {!! json_encode($post->content) !!};

        // ✅ บันทึกค่าลง input hidden ก่อนส่ง form
        quill.on('text-change', function() {
            document.getElementById('content').value = quill.root.innerHTML;
        });

        // ✅ ป้องกันกรณีที่ Quill ไม่ได้แก้ไขอะไร แต่ต้องส่งค่าไปด้วย
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById('content').value = quill.root.innerHTML;
        });
    </script>

</body>

</html>
