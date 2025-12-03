<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Rekap Kualitas Udara</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        
        /* DSEG7 - Better LED segmented font */
        @font-face {
            font-family: 'DSEG7';
            src: url('https://cdn.jsdelivr.net/npm/dseg@0.46.0/fonts/DSEG7-Classic/DSEG7Classic-Bold.woff2') format('woff2');
            font-weight: bold;
            font-style: normal;
        }
        
        .digital-display {
            font-family: 'DSEG7', 'Courier New', monospace;
            letter-spacing: 0.1em;
            font-weight: bold;
        }
        
        @keyframes slide-up {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .animate-slide-up {
            animation: slide-up 0.6s ease-out;
        }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen">
    <!-- Background Pattern -->
    <div class="fixed inset-0 opacity-5">
        <div class="absolute inset-0" style="background-image: repeating-linear-gradient(0deg, transparent, transparent 2px, cyan 2px, cyan 4px); background-size: 100% 4px;"></div>
    </div>
    
    <!-- Navbar -->
    <nav class="relative border-b border-slate-800/50 backdrop-blur-xl bg-slate-900/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-cyan-500 to-teal-600 rounded-lg flex items-center justify-center shadow-lg shadow-cyan-500/50">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path>
                        </svg>
                    </div>
                    <span class="text-xl font-bold bg-gradient-to-r from-cyan-400 to-teal-400 bg-clip-text text-transparent">
                        AirQuality Monitor
                    </span>
                </div>
                <div class="flex space-x-1">
                    <a href="{{ route('iot.index') }}" class="px-4 py-2 text-sm font-medium text-slate-400 hover:text-white hover:bg-cyan-600/10 hover:border-cyan-500/30 border border-transparent rounded-lg transition-all">
                        Real-time
                    </a>
                    <a href="{{ route('iot.dashboard') }}" class="px-4 py-2 text-sm font-medium text-white bg-cyan-600/20 border border-cyan-500/50 rounded-lg transition-all shadow-lg shadow-cyan-500/20">
                        Dashboard
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
        <!-- Header -->
        <div class="text-center mb-12 animate-slide-up">
            <h1 class="text-4xl sm:text-5xl font-bold mb-4 bg-gradient-to-r from-cyan-400 via-teal-400 to-emerald-400 bg-clip-text text-transparent">
                Dashboard Analitik
            </h1>
            <p class="text-slate-400 text-lg">Rekap & Visualisasi Data 24 Jam Terakhir</p>
        </div>

        <!-- Statistics Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 animate-slide-up" style="animation-delay: 0.1s;">
            <!-- Current PPM -->
            <div class="bg-slate-900/80 backdrop-blur-sm border border-cyan-500/30 rounded-xl p-6 shadow-xl shadow-cyan-500/10 hover:border-cyan-500/50 hover:shadow-cyan-500/20 transition-all group">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-cyan-500 to-teal-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform shadow-lg shadow-cyan-500/30">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                </div>
                <div class="text-xs font-semibold uppercase tracking-wider text-slate-500 mb-3">PPM Saat Ini</div>
                <div class="digital-display text-5xl font-bold text-cyan-400 mb-1">
                    {{ number_format($stats['current']->ppm ?? 0, 2) }}
                </div>
                <div class="text-xs text-slate-400 mt-2 uppercase tracking-wide">Parts Per Million</div>
            </div>

            <!-- Average 24h -->
            <div class="bg-slate-900/80 backdrop-blur-sm border border-blue-500/30 rounded-xl p-6 shadow-xl shadow-blue-500/10 hover:border-blue-500/50 hover:shadow-blue-500/20 transition-all group">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-blue-500/20 rounded-lg flex items-center justify-center border border-blue-500/30 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                </div>
                <div class="text-xs font-semibold uppercase tracking-wider text-slate-500 mb-3">Rata-rata 24 Jam</div>
                <div class="digital-display text-5xl font-bold text-blue-400 mb-1">
                    {{ number_format($stats['avg_24h'] ?? 0, 2) }}
                </div>
                <div class="text-xs text-slate-400 mt-2 uppercase tracking-wide">PPM Average</div>
            </div>

            <!-- Maximum 24h -->
            <div class="bg-slate-900/80 backdrop-blur-sm border border-red-500/30 rounded-xl p-6 shadow-xl shadow-red-500/10 hover:border-red-500/50 hover:shadow-red-500/20 transition-all group">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-red-500/20 rounded-lg flex items-center justify-center border border-red-500/30 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                        </svg>
                    </div>
                </div>
                <div class="text-xs font-semibold uppercase tracking-wider text-slate-500 mb-3">Maksimum 24 Jam</div>
                <div class="digital-display text-5xl font-bold text-red-400 mb-1">
                    {{ number_format($stats['max_24h'] ?? 0, 2) }}
                </div>
                <div class="text-xs text-slate-400 mt-2 uppercase tracking-wide">PPM Peak</div>
            </div>

            <!-- Minimum 24h -->
            <div class="bg-slate-900/80 backdrop-blur-sm border border-emerald-500/30 rounded-xl p-6 shadow-xl shadow-emerald-500/10 hover:border-emerald-500/50 hover:shadow-emerald-500/20 transition-all group">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-emerald-500/20 rounded-lg flex items-center justify-center border border-emerald-500/30 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                        </svg>
                    </div>
                </div>
                <div class="text-xs font-semibold uppercase tracking-wider text-slate-500 mb-3">Minimum 24 Jam</div>
                <div class="digital-display text-5xl font-bold text-emerald-400 mb-1">
                    {{ number_format($stats['min_24h'] ?? 0, 2) }}
                </div>
                <div class="text-xs text-slate-400 mt-2 uppercase tracking-wide">PPM Lowest</div>
            </div>
        </div>

        <!-- Charts Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Line Chart - Takes 2 columns -->
            <div class="lg:col-span-2 bg-slate-900/80 backdrop-blur-sm border border-cyan-500/30 rounded-2xl p-8 shadow-xl shadow-cyan-500/10 animate-slide-up" style="animation-delay: 0.2s;">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-white mb-2">Grafik Kadar PPM Per Jam</h2>
                    <p class="text-sm text-slate-400">Monitoring kadar polutan udara (CO2) dalam 24 jam terakhir</p>
                </div>
                <div class="h-80">
                    <canvas id="ppmChart"></canvas>
                </div>
            </div>

            <!-- Doughnut Chart - Takes 1 column -->
            <div class="bg-slate-900/80 backdrop-blur-sm border border-teal-500/30 rounded-2xl p-8 shadow-xl shadow-teal-500/10 animate-slide-up" style="animation-delay: 0.3s;">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-white mb-2">Distribusi Status</h2>
                    <p class="text-sm text-slate-400">Persentase kualitas udara</p>
                </div>
                <div class="h-80 flex items-center justify-center">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Update Info -->
        <div class="text-center animate-slide-up" style="animation-delay: 0.4s;">
            <div class="inline-flex items-center space-x-3 px-6 py-3 bg-slate-900/50 border border-cyan-500/30 rounded-full shadow-lg shadow-cyan-500/10">
                <span class="relative flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-cyan-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-cyan-500"></span>
                </span>
                <span class="text-sm text-slate-400 font-medium">Dashboard diperbarui otomatis setiap 1 jam</span>
            </div>
        </div>
    </div>

    <script>
        // Data dari Laravel untuk Line Chart
        const hourlyData = @json($hourlyData);
        const labels = Object.keys(hourlyData);
        const dataPoints = labels.map(key => hourlyData[key].avg_ppm);

        // Line Chart Configuration dengan warna baru
        const ctx1 = document.getElementById('ppmChart').getContext('2d');
        
        const gradient = ctx1.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(6, 182, 212, 0.5)');    // cyan
        gradient.addColorStop(0.5, 'rgba(20, 184, 166, 0.3)'); // teal
        gradient.addColorStop(1, 'rgba(16, 185, 129, 0.1)');   // emerald

        new Chart(ctx1, {
            type: 'line',
            data: {
                labels: labels.map(label => {
                    const date = new Date(label);
                    return date.getHours() + ':00';
                }),
                datasets: [{
                    label: 'Kadar PPM',
                    data: dataPoints,
                    borderColor: 'rgb(6, 182, 212)',
                    backgroundColor: gradient,
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    pointBackgroundColor: 'rgb(6, 182, 212)',
                    pointBorderColor: 'rgb(15, 23, 42)',
                    pointBorderWidth: 2,
                    pointHoverBackgroundColor: 'rgb(34, 211, 238)',
                    pointHoverBorderColor: 'rgb(6, 182, 212)',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgb(15, 23, 42)',
                        titleColor: 'rgb(6, 182, 212)',
                        bodyColor: 'rgb(148, 163, 184)',
                        borderColor: 'rgb(6, 182, 212)',
                        borderWidth: 1,
                        padding: 12,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return 'PPM: ' + context.parsed.y.toFixed(2);
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(6, 182, 212, 0.1)',
                            drawBorder: false
                        },
                        ticks: {
                            color: 'rgb(148, 163, 184)',
                            font: {
                                family: 'Inter',
                                size: 11
                            }
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(6, 182, 212, 0.05)',
                            drawBorder: false
                        },
                        ticks: {
                            color: 'rgb(148, 163, 184)',
                            maxRotation: 45,
                            minRotation: 45,
                            font: {
                                size: 11
                            }
                        }
                    }
                }
            }
        });

        // Doughnut Chart - Status Distribution
        const ctx2 = document.getElementById('statusChart').getContext('2d');
        const statusDistributionData = @json($statusDistribution);

        new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: Object.keys(statusDistributionData),
                datasets: [{
                    data: Object.values(statusDistributionData),
                    backgroundColor: [
                        'rgba(16, 185, 129, 0.8)',   // Baik - emerald
                        'rgba(234, 179, 8, 0.8)',    // Kurang Baik - yellow
                        'rgba(249, 115, 22, 0.8)',   // Buruk - orange
                        'rgba(239, 68, 68, 0.8)'     // Bahaya - red
                    ],
                    borderColor: [
                        'rgb(16, 185, 129)',
                        'rgb(234, 179, 8)',
                        'rgb(249, 115, 22)',
                        'rgb(239, 68, 68)'
                    ],
                    borderWidth: 2,
                    hoverOffset: 10,
                    hoverBorderWidth: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: 'rgb(203, 213, 225)',
                            padding: 15,
                            font: {
                                size: 12,
                                family: 'Inter'
                            },
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgb(15, 23, 42)',
                        titleColor: 'rgb(6, 182, 212)',
                        bodyColor: 'rgb(148, 163, 184)',
                        borderColor: 'rgb(6, 182, 212)',
                        borderWidth: 1,
                        padding: 12,
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                return label + ': ' + value + ' (' + percentage + '%)';
                            }
                        }
                    }
                },
                cutout: '65%'
            }
        });
    </script>
</body>
</html>