@extends('layouts.admin')

@section('content')
<div class="container mx-auto p-6 bg-white shadow-md rounded-lg">
    <h1 class="text-3xl font-bold mb-6 text-green-700 flex items-center">
        📊 Log Statistics
    </h1>

    <!-- Layout ใช้ Flexbox -->
    <div class="flex flex-col lg:flex-row gap-6">
        <!-- กราฟแท่ง (ใหญ่กว่า) -->
        <div class="lg:w-2/3 bg-gray-100 p-6 rounded-lg">
            <h2 class="text-xl font-semibold text-gray-800 mb-3 flex items-center">
                📌 Log Count by Action
            </h2>
            <div class="relative w-full">
                <canvas id="logBarChart" style="width:100%; height:400px;"></canvas>
            </div>
        </div>

        <!-- กราฟเส้น (ขนาดเล็กกว่า) -->
        <div class="lg:w-1/3 bg-gray-100 p-6 rounded-lg">
            <h2 class="text-xl font-semibold text-gray-800 mb-3 flex items-center">
                📈 Log Trends (Last 7 Days)
            </h2>
            <div class="relative w-full">
                <canvas id="logLineChart" style="width:100%; height:400px;" ></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // ข้อมูล Log Count by Action (Bar Chart)
    const logStats = @json($logStats);
    const logLabels = logStats.map(log => log.action);
    const logCounts = logStats.map(log => log.count);
    const barColors = logLabels.map(() => getRandomColor()); // ทำให้แท่งเป็นคนละสี

    const ctxBar = document.getElementById('logBarChart').getContext('2d');
    new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: logLabels,
            datasets: [{
                label: 'Log Count',
                data: logCounts,
                backgroundColor: barColors, // ใช้สีที่สุ่มมา
                borderColor: barColors.map(color => color.replace('0.6', '1')), // เส้นขอบเข้มขึ้น
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false, // ป้องกันยืดลง
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // ข้อมูล Log Trends (Line Chart)
    const logTrends = @json($logTrends);
    const trendLabels = [...new Set(logTrends.map(log => log.date))]; // วันที่
    const actions = [...new Set(logTrends.map(log => log.action))]; // ประเภท Log

    const trendDatasets = actions.map(action => ({
        label: action,
        data: trendLabels.map(date => {
            const entry = logTrends.find(log => log.date === date && log.action === action);
            return entry ? entry.count : 0;
        }),
        borderColor: getRandomColor(),
        borderWidth: 2,
        pointBackgroundColor: 'rgba(34, 139, 34, 1)',
        fill: false
    }));

    const ctxLine = document.getElementById('logLineChart').getContext('2d');
    new Chart(ctxLine, {
        type: 'line',
        data: {
            labels: trendLabels,
            datasets: trendDatasets
        },
        options: {
            responsive: true,
            maintainAspectRatio: false, // ป้องกันยืดลง
            plugins: {
                legend: { position: 'top' }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // ฟังก์ชันสุ่มสีสำหรับ Bar Chart & Line Chart
    function getRandomColor() {
        const colors = [
            'rgba(34, 139, 34, 0.6)', // เขียว KU
            'rgba(60, 179, 113, 0.6)', // เขียวอ่อน
            'rgba(46, 204, 113, 0.6)', // เขียวสด
            'rgba(255, 99, 132, 0.6)', // แดง
            'rgba(54, 162, 235, 0.6)', // ฟ้า
            'rgba(255, 206, 86, 0.6)' // เหลือง
        ];
        return colors[Math.floor(Math.random() * colors.length)];
    }
</script>
@endsection
