<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Rekap Kualitas Udara</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0a0e27 0%, #1a1f3a 50%, #0f1419 100%);
            color: #ffffff;
            min-height: 100vh;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 20% 50%, rgba(74, 144, 226, 0.1) 0%, transparent 50%), radial-gradient(circle at 80% 80%, rgba(138, 43, 226, 0.1) 0%, transparent 50%);
            pointer-events: none;
            animation: bgPulse 15s ease-in-out infinite;
        }

        .navbar {
            background: rgba(15, 20, 35, 0.8);
            backdrop-filter: blur(10px);
            padding: 20px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 24px;
            font-weight: 700;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: 1px;
        }

        .nav-links a {
            color: #ffffff;
            text-decoration: none;
            margin-left: 30px;
            font-weight: 500;
            transition: all 0.3s ease;
            padding: 8px 20px;
            border-radius: 8px;
            font-size: 14px;
        }

        .nav-links a:hover {
            background: rgba(102, 126, 234, 0.2);
            color: #667eea;
        }

        .nav-links a.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 60px 40px;
            position: relative;
            z-index: 1;
        }

        .header {
            text-align: center;
            margin-bottom: 60px;
        }

        .header h1 {
            font-size: 48px;
            font-weight: 700;
            margin-bottom: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .header p {
            color: #a0aec0;
            font-size: 18px;
            font-weight: 300;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: rgba(15, 20, 35, 0.6);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 30px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(102, 126, 234, 0.2);
        }

        .stat-label {
            font-size: 13px;
            color: #a0aec0;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .stat-value {
            font-family: 'Orbitron', monospace;
            font-size: 42px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .stat-unit {
            font-size: 16px;
            color: #a0aec0;
            font-weight: 400;
        }

        .chart-container {
            background: rgba(15, 20, 35, 0.6);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 40px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 30px;
        }

        .chart-header {
            margin-bottom: 30px;
        }

        .chart-header h2 {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 10px;
            color: #ffffff;
        }

        .chart-header p {
            color: #a0aec0;
            font-size: 14px;
        }

        .chart-wrapper {
            position: relative;
            height: 400px;
        }

        .update-info {
            text-align: center;
            margin-top: 30px;
            padding: 20px;
            background: rgba(15, 20, 35, 0.4);
            border-radius: 15px;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .update-info p {
            color: #a0aec0;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .refresh-indicator {
            width: 12px;
            height: 12px;
            background: #667eea;
            border-radius: 50%;
            animation: blink 2s ease-in-out infinite;
        }
        
        /* === Animations === */
        @keyframes bgPulse {
            0%, 100% { opacity: 0.3; }
            50% { opacity: 0.6; }
        }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }

        /* === Media Queries === */
        @media (max-width: 768px) {
            .container {
                padding: 30px 20px;
            }
            .header h1 {
                font-size: 32px;
            }
            .stat-value {
                font-size: 32px;
            }
            .chart-wrapper {
                height: 300px;
            }
            .chart-container {
                padding: 25px;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <div class="logo">AirQuality Monitor</div>
            <div class="nav-links">
                <a href="{{ route('iot.index') }}">Real-time</a>
                <a href="{{ route('iot.dashboard') }}" class="active">Dashboard</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="header">
            <h1>Dashboard Analitik</h1>
            <p>Rekap & Visualisasi Data 24 Jam Terakhir</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card" style="color: #667eea;">
                <div class="stat-label">PPM Saat Ini</div>
                <div class="stat-value">{{ number_format($stats['current']->ppm ?? 0, 2) }} <span class="stat-unit">PPM</span></div>
            </div>
            <div class="stat-card" style="color: #10b981;">
                <div class="stat-label">Rata-rata 24 Jam</div>
                <div class="stat-value">{{ number_format($stats['avg_24h'] ?? 0, 2) }} <span class="stat-unit">PPM</span></div>
            </div>
            <div class="stat-card" style="color: #ef4444;">
                <div class="stat-label">Maksimum 24 Jam</div>
                <div class="stat-value">{{ number_format($stats['max_24h'] ?? 0, 2) }} <span class="stat-unit">PPM</span></div>
            </div>
            <div class="stat-card" style="color: #3b82f6;">
                <div class="stat-label">Minimum 24 Jam</div>
                <div class="stat-value">{{ number_format($stats['min_24h'] ?? 0, 2) }} <span class="stat-unit">PPM</span></div>
            </div>
        </div>

        <div class="chart-container">
            <div class="chart-header">
                <h2>Grafik Kadar PPM Per Jam</h2>
                <p>Monitoring kadar polutan udara (CO2) dalam 24 jam terakhir</p>
            </div>
            <div class="chart-wrapper"><canvas id="ppmChart"></canvas></div>
        </div>

        <div class="chart-container">
            <div class="chart-header">
                <h2>Distribusi Status Kualitas Udara</h2>
                <p>Persentase kategori kualitas udara dalam 24 jam terakhir</p>
            </div>
            <div class="chart-wrapper"><canvas id="statusChart"></canvas></div>
        </div>

        <div class="update-info">
            <p><span class="refresh-indicator"></span> Dashboard diperbarui otomatis setiap 1 jam</p>
        </div>
    </div>

    <script>
        // Data dari Laravel untuk Line Chart
        const hourlyData = @json($hourlyData);
        const labels = Object.keys(hourlyData).map(time => new Date(time).toLocaleString('id-ID', { month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit'}));
        const ppmValues = Object.values(hourlyData).map(item => item.avg_ppm);

        // Chart PPM Per Jam (Line Chart)
        const ctx1 = document.getElementById('ppmChart').getContext('2d');
        const gradient1 = ctx1.createLinearGradient(0, 0, 0, 400);
        gradient1.addColorStop(0, 'rgba(102, 126, 234, 0.8)');
        gradient1.addColorStop(1, 'rgba(118, 75, 162, 0.1)');
        new Chart(ctx1, { type: 'line', data: { labels: labels, datasets: [{ label: 'Kadar PPM', data: ppmValues, backgroundColor: gradient1, borderColor: '#667eea', borderWidth: 3, fill: true, tension: 0.4, pointRadius: 5, pointHoverRadius: 8, pointBackgroundColor: '#667eea', pointBorderColor: '#ffffff', pointBorderWidth: 2, pointHoverBackgroundColor: '#ffffff', pointHoverBorderColor: '#667eea', pointHoverBorderWidth: 3 }] }, options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: true, labels: { color: '#ffffff', font: { size: 14, family: 'Inter' }, padding: 20 } }, tooltip: { backgroundColor: 'rgba(15, 20, 35, 0.95)', titleColor: '#ffffff', bodyColor: '#a0aec0', borderColor: '#667eea', borderWidth: 1, padding: 15, displayColors: true, callbacks: { label: (context) => 'PPM: ' + context.parsed.y.toFixed(2) } } }, scales: { x: { grid: { color: 'rgba(255, 255, 255, 0.05)' }, ticks: { color: '#a0aec0', font: { size: 11 }, maxRotation: 45, minRotation: 45 } }, y: { grid: { color: 'rgba(255, 255, 255, 0.05)' }, ticks: { color: '#a0aec0', font: { size: 12 }, callback: (value) => value + ' PPM' }, beginAtZero: true } }, interaction: { intersect: false, mode: 'index' } } });

        // Chart Distribusi Status (Doughnut Chart)
        const ctx2 = document.getElementById('statusChart').getContext('2d');
        const statusDistributionData = @json($statusDistribution);

        new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: Object.keys(statusDistributionData),
                datasets: [{
                    data: Object.values(statusDistributionData),
                    backgroundColor: [
                        'rgba(16, 185, 129, 0.8)',   // Baik
                        'rgba(245, 158, 11, 0.8)',  // Normal
                        'rgba(239, 68, 68, 0.8)',  // Kurang Baik
                        'rgba(220, 38, 38, 0.8)',  // Buruk
                        'rgba(139, 0, 0, 0.8)'      // Bahaya
                    ],
                    borderColor: [
                        '#10b981', '#f59e0b', '#ef4444', '#dc2626', '#8b0000'
                    ],
                    borderWidth: 2,
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { color: '#ffffff', font: { size: 14, family: 'Inter' }, padding: 20, usePointStyle: true, pointStyle: 'circle' } },
                    tooltip: {
                        backgroundColor: 'rgba(15, 20, 35, 0.95)', titleColor: '#ffffff', bodyColor: '#a0aec0', borderColor: '#667eea', borderWidth: 1, padding: 15,
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                if (total === 0) return context.label + ': 0 (0%)';
                                const percentage = ((context.parsed / total) * 100).toFixed(1);
                                return context.label + ': ' + context.parsed + ' data points (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });

        setTimeout(() => location.reload(), 3600000); // Auto refresh 1 jam
    </script>
</body>
</html>