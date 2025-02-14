<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post List - Learn Up</title>
    @vite('resources/css/app.css')
    <link rel="icon" href="{{ asset('bookshelf.ico') }}" type="image/x-icon">
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="Sat, 01 Jan 2000 00:00:00 GMT">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- ใช้ jQuery สำหรับ AJAX -->
</head>

<body class="bg-gray-100">

    @include('components.navbar')

    <div class="max-w-screen-lg mx-auto mt-5 mt-[100px]">
        <!-- Search and Filter Form -->
        <div class="mb-4 flex justify-between items-center bg-white p-4 rounded-md shadow-md">
            <input type="text" id="search-input" placeholder="Search posts..."
                class="w-2/3 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">

            <div class="flex items-center space-x-3">
                <label class="flex items-center">
                    <input type="checkbox" class="status-filter" value="approved">
                    <span class="ml-2 text-green-600">Approved</span>
                </label>
                <label class="flex items-center">
                    <input type="checkbox" class="status-filter" value="pending">
                    <span class="ml-2 text-yellow-600">Pending</span>
                </label>
                <label class="flex items-center">
                    <input type="checkbox" class="status-filter" value="rejected">
                    <span class="ml-2 text-red-600">Rejected</span>
                </label>
            </div>
        </div>

        <!-- Table -->
        <div id="post-table">
            @include('user.posts.partials.post_table', ['posts' => $posts])
        </div>

    </div>

    <script>
        $(document).ready(function () {
            function fetchPosts() {
                let search = $('#search-input').val();
                let statuses = [];
                $('.status-filter:checked').each(function () {
                    statuses.push($(this).val());
                });

                $.ajax({
                    url: "{{ route('user.posts.index') }}",
                    method: "GET",
                    data: { search: search, status: statuses },
                    success: function (data) {
                        $('#post-table').html(data);
                    }
                });
            }

            $('#search-input').on('keyup', function () {
                fetchPosts();
            });

            $('.status-filter').on('change', function () {
                fetchPosts();
            });
        });
    </script>

</body>

</html>
