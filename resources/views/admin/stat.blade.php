@extends('layouts.admin')

@section('content')
<div class="container mx-auto p-6 bg-white shadow-md rounded-lg">
    <h1 class="text-3xl font-bold mb-6 text-green-700">📊 Log Statistics</h1>

    <!-- กราฟแท่ง: จำนวน Log ตามประเภท -->
    <div class="mb-8">
        <h2 class="text-xl font-semibold text-gray-800 mb-3">📌 Log Count by Action</h2>
        <div class="bg-gray-100 p-4 rounded-lg">
            <canvas id="logBarChart"></canvas>
        </div>
    </div>

    <!-- กราฟเส้น: แนวโน้ม Log ตามเวลา -->
    <div class="mb-8">
        <h2 class="text-xl font-semibold text-gray-800 mb-3">📈 Log Trends (Last 7 Days)</h2>
        <div class="bg-gray-100 p-4 rounded-lg">
            <canvas id="logLineChart"></canvas>
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

    const ctxBar = document.getElementById('logBarChart').getContext('2d');
    new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: logLabels,
            datasets: [{
                label: 'Log Count',
                data: logCounts,
                backgroundColor: 'rgba(34, 139, 34, 0.6)', // สีเขียวหลัก
                borderColor: 'rgba(34, 139, 34, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
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
        pointBackgroundColor: 'rgba(34, 139, 34, 1)', // จุดสีเขียว
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
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // ฟังก์ชันสุ่มสีสำหรับ Line Chart
    function getRandomColor() {
        const colors = [
            'rgba(34, 139, 34, 1)', // เขียว KU
            'rgba(60, 179, 113, 1)', // เขียวอ่อน
            'rgba(46, 204, 113, 1)', // เขียวสด
            'rgba(255, 99, 132, 1)', // แดง
            'rgba(54, 162, 235, 1)', // ฟ้า
            'rgba(255, 206, 86, 1)' // เหลือง
        ];
        return colors[Math.floor(Math.random() * colors.length)];
    }
</script>
@endsection
