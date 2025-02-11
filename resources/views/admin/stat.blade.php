@extends('layouts.admin')

@section('content')
    <div class="container mx-auto p-6 bg-white shadow-md rounded-lg">
        <h1 class="text-3xl font-bold mb-6 text-green-700 flex items-center">
            📊 Log Statistics
        </h1>

        <!-- สถิติรวม -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
            <div class="bg-green-500 text-white p-4 rounded-lg shadow-md text-center">
                <h3 class="text-xl font-semibold">📜 Total Posts</h3>
                <p class="text-3xl font-bold">{{ $totalPosts }}</p>
            </div>
            <div class="bg-blue-500 text-white p-4 rounded-lg shadow-md text-center">
                <h3 class="text-xl font-semibold">👥 Total Users</h3>
                <p class="text-3xl font-bold">{{ $totalUsers }}</p>
            </div>
            <div class="bg-yellow-500 text-white p-4 rounded-lg shadow-md text-center">
                <h3 class="text-xl font-semibold">✍️ Writers</h3>
                <p class="text-3xl font-bold">{{ $totalWriters }}</p>
            </div>
            <div class="bg-purple-500 text-white p-4 rounded-lg shadow-md text-center">
                <h3 class="text-xl font-semibold">🔧 Admins</h3>
                <p class="text-3xl font-bold">{{ $totalAdmins }}</p>
            </div>
            <div class="bg-red-500 text-white p-4 rounded-lg shadow-md text-center">
                <h3 class="text-xl font-semibold">📊 Total Logs</h3>
                <p class="text-3xl font-bold">{{ $totalLogs }}</p>
            </div>
        </div>
        <!-- Layout ใช้ Flexbox -->
        <div class="flex flex-col lg:flex-row gap-6">
            <div class="lg:w-2/3 bg-gray-100 p-6 rounded-lg">
                <h2 class="text-xl font-semibold text-gray-800 mb-3 flex items-center">
                    📌 Log Count by Action
                </h2>
                <div class="relative w-full">
                    <canvas id="logBarChart" style="width:100%; height:700px;"></canvas>
                </div>
            </div>
            <div class="lg:w-1/3 bg-gray-100 p-6 rounded-lg">
                <h2 class="text-xl font-semibold text-gray-800 mb-3 flex items-center">
                    📈 Log Trends (Last 7 Days)
                </h2>
                <div class="relative w-full">
                    <canvas id="logLineChart" style="width:100%; height:700px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // ตรวจสอบว่าข้อมูลใน Blade ถูกแปลงเป็น JSON
        const logStats = @json($logStats);
        const logLabels = logStats.map(log => log.action);
        const logCounts = logStats.map(log => log.count);

        const logTrends = @json($logTrends);
        const trendLabels = [...new Set(logTrends.map(log => log.date))];
        const actions = [...new Set(logTrends.map(log => log.action))];

        // สร้างสีแบบ Mapping เพื่อให้สีของประเภทเดียวกันเหมือนกัน
        const actionColors = {};
        actions.forEach((action, index) => {
            actionColors[action] = getColorByIndex(index);
        });

        // Bar Chart
        new Chart(document.getElementById('logBarChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: logLabels,
                datasets: [{
                    label: 'Log Count',
                    data: logCounts,
                    backgroundColor: logLabels.map(action => actionColors[action]),
                    borderColor: logLabels.map(action => actionColors[action].replace('0.6', '1')),
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Line Chart
        new Chart(document.getElementById('logLineChart').getContext('2d'), {
            type: 'line',
            data: {
                labels: trendLabels,
                datasets: actions.map(action => ({
                    label: action,
                    data: trendLabels.map(date => {
                        const entry = logTrends.find(log => log.date === date && log.action ===
                            action);
                        return entry ? entry.count : 0;
                    }),
                    borderColor: actionColors[action],
                    borderWidth: 2,
                    pointBackgroundColor: actionColors[action].replace('0.6', '1'),
                    fill: false
                }))
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        function getColorByIndex(index) {
            const colors = [
                'rgba(34, 139, 34, 0.6)', // เขียวเข้ม
                'rgba(60, 179, 113, 0.6)', // เขียวอ่อน
                'rgba(46, 204, 113, 0.6)', // เขียวสด
                'rgba(255, 99, 132, 0.6)', // แดง
                'rgba(54, 162, 235, 0.6)', // ฟ้า
                'rgba(255, 206, 86, 0.6)', // เหลือง
                'rgba(128, 0, 128, 0.6)', // ม่วง
                'rgba(255, 165, 0, 0.6)', // ส้ม
                'rgba(75, 0, 130, 0.6)', // ม่วงเข้ม
                'rgba(0, 255, 255, 0.6)', // ฟ้าคราม
                'rgba(255, 69, 0, 0.6)', // ส้มแดง
                'rgba(128, 128, 0, 0.6)', // เหลืองอมเขียว
                'rgba(255, 20, 147, 0.6)', // DeepPink
                'rgba(30, 144, 255, 0.6)', // DodgerBlue
                'rgba(250, 128, 114, 0.6)' // Salmon
            ];
            return colors[index % colors.length];
        }
    </script>
@endsection
