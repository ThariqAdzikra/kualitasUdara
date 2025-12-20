<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoring Kualitas Udara - Real-time</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
            letter-spacing: 0.15em;
            font-weight: bold;
            text-shadow: 
                0 0 20px rgba(6, 182, 212, 0.8),
                0 0 40px rgba(6, 182, 212, 0.4),
                0 0 60px rgba(6, 182, 212, 0.2);
        }
        
        /* Digital Card Background */
        .digital-card {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            border: 2px solid rgba(6, 182, 212, 0.3);
            box-shadow: 
                0 0 30px rgba(6, 182, 212, 0.1),
                inset 0 0 30px rgba(6, 182, 212, 0.05);
        }
        
        @keyframes pulse-glow {
            0%, 100% { 
                opacity: 1;
                text-shadow: 
                    0 0 20px rgba(6, 182, 212, 0.8),
                    0 0 40px rgba(6, 182, 212, 0.4),
                    0 0 60px rgba(6, 182, 212, 0.2);
            }
            50% { 
                opacity: 0.8;
                text-shadow: 
                    0 0 30px rgba(6, 182, 212, 1),
                    0 0 50px rgba(6, 182, 212, 0.6),
                    0 0 70px rgba(6, 182, 212, 0.3);
            }
        }
        .animate-pulse-glow {
            animation: pulse-glow 2s ease-in-out infinite;
        }
        
        @keyframes slide-up {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .animate-slide-up {
            animation: slide-up 0.6s ease-out;
        }
        
        /* Smooth value transition */
        .value-transition {
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen">
    
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
                    <a href="{{ route('iot.index') }}" class="px-4 py-2 text-sm font-medium text-white bg-cyan-600/20 border border-cyan-500/50 rounded-lg transition-all shadow-lg shadow-cyan-500/20">
                        Real-time
                    </a>
                    <a href="{{ route('iot.dashboard') }}" class="px-4 py-2 text-sm font-medium text-slate-400 hover:text-white hover:bg-cyan-600/10 hover:border-cyan-500/30 border border-transparent rounded-lg transition-all">
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
                Monitoring Kualitas Udara
            </h1>
            <p class="text-slate-400 text-lg">Pemantauan Real-time Kadar Polutan Udara (CO2)</p>
        </div>

        <!-- Main Display Card - Digital Style -->
        <div class="mb-8 animate-slide-up" style="animation-delay: 0.1s;">
            <div class="digital-card rounded-3xl p-8 sm:p-12 relative overflow-hidden">
                <!-- Digital Grid Effect -->
                <div class="absolute inset-0 opacity-10 pointer-events-none">
                    <div class="absolute inset-0" style="background-image: linear-gradient(rgba(6, 182, 212, 0.1) 1px, transparent 1px), linear-gradient(90deg, rgba(6, 182, 212, 0.1) 1px, transparent 1px); background-size: 20px 20px;"></div>
                </div>
                
                <!-- Content -->
                <div class="relative z-10">
                    <!-- PPM Label -->
                    <div class="text-center mb-6">
                        <p class="text-xs sm:text-sm font-semibold uppercase tracking-widest text-cyan-400/70">CARBON DIOXIDE LEVEL</p>
                    </div>
                    
                    <!-- PPM Display - Digital LED Style -->
                    <div class="text-center mb-8">
                        <div id="ppm-display" class="digital-display text-7xl sm:text-8xl md:text-9xl font-bold text-cyan-400 animate-pulse-glow value-transition">
                        {{ number_format($ppm, 0) }}
                    </div>
                        <div class="mt-4 text-2xl text-cyan-400/60 font-semibold tracking-wider">PPM</div>
                    </div>

                    <!-- Status & Trend Row -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-w-2xl mx-auto">
                        <!-- Status Badge -->
                        <div class="flex items-center justify-center">
                            <div id="status-badge" class="px-6 py-3 rounded-xl border-2 font-semibold text-lg value-transition
                                {{ $kategoriCode == 1 ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/50 shadow-lg shadow-emerald-500/20' : '' }}
                                {{ $kategoriCode == 3 ? 'bg-yellow-500/10 text-yellow-400 border-yellow-500/50 shadow-lg shadow-yellow-500/20' : '' }}
                                {{ $kategoriCode == 4 ? 'bg-orange-500/10 text-orange-400 border-orange-500/50 shadow-lg shadow-orange-500/20' : '' }}
                                {{ $kategoriCode == 5 ? 'bg-red-500/10 text-red-400 border-red-500/50 shadow-lg shadow-red-500/20' : '' }}">
                                <span class="flex items-center space-x-2">
                                    <span class="w-2.5 h-2.5 rounded-full 
                                        {{ $kategoriCode == 1 ? 'bg-emerald-400' : '' }}
                                        {{ $kategoriCode == 3 ? 'bg-yellow-400' : '' }}
                                        {{ $kategoriCode == 4 ? 'bg-orange-400' : '' }}
                                        {{ $kategoriCode == 5 ? 'bg-red-400' : '' }}
                                        animate-pulse"></span>
                                    <span id="kategori-text" class="uppercase tracking-wide">{{ $kategori }}</span>
                                </span>
                            </div>
                        </div>

                        <!-- Trend Indicator -->
                        <div class="flex items-center justify-center">
                            <div id="trend-badge" class="px-6 py-3 rounded-xl border-2 font-semibold text-lg value-transition
                                {{ $arahCode == 1 ? 'bg-red-500/10 text-red-400 border-red-500/50 shadow-lg shadow-red-500/20' : '' }}
                                {{ $arahCode == 0 ? 'bg-cyan-500/10 text-cyan-400 border-cyan-500/50 shadow-lg shadow-cyan-500/20' : '' }}
                                {{ $arahCode == -1 ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/50 shadow-lg shadow-emerald-500/20' : '' }}">
                                <span class="flex items-center space-x-2">
                                    @if($arahCode == 1)
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 15l7-7 7 7"></path>
                                        </svg>
                                    @elseif($arahCode == -1)
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 12h14"></path>
                                        </svg>
                                    @endif
                                    <span id="arah-text" class="uppercase tracking-wide">{{ $arah }}</span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info Cards Grid - NOW WITH DIGITAL FONT -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 animate-slide-up" style="animation-delay: 0.2s;">
            <!-- Safe Range Card -->
            <div class="bg-slate-900/80 backdrop-blur-sm border border-emerald-500/30 rounded-xl p-6 shadow-xl shadow-emerald-500/10 hover:border-emerald-500/50 hover:shadow-emerald-500/20 transition-all">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-12 h-12 bg-emerald-500/20 rounded-lg flex items-center justify-center border border-emerald-500/30">
                        <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-slate-400">Batas Aman</h3>
                </div>
                <div class="digital-display text-4xl sm:text-5xl font-bold text-emerald-400 mb-2">≤ 800</div>
                <p class="text-sm text-slate-500">PPM - Kualitas Baik</p>
            </div>

            <!-- Warning Range Card -->
            <div class="bg-slate-900/80 backdrop-blur-sm border border-yellow-500/30 rounded-xl p-6 shadow-xl shadow-yellow-500/10 hover:border-yellow-500/50 hover:shadow-yellow-500/20 transition-all">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-12 h-12 bg-yellow-500/20 rounded-lg flex items-center justify-center border border-yellow-500/30">
                        <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-slate-400">Perlu Perhatian</h3>
                </div>
                <div class="digital-display text-3xl sm:text-4xl font-bold text-yellow-400 mb-2">801-2000</div>
                <p class="text-sm text-slate-500">PPM - Kurang Baik</p>
            </div>

            <!-- Danger Range Card -->
            <div class="bg-slate-900/80 backdrop-blur-sm border border-red-500/30 rounded-xl p-6 shadow-xl shadow-red-500/10 hover:border-red-500/50 hover:shadow-red-500/20 transition-all">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-12 h-12 bg-red-500/20 rounded-lg flex items-center justify-center border border-red-500/30">
                        <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-slate-400">Zona Bahaya</h3>
                </div>
                <div class="digital-display text-4xl sm:text-5xl font-bold text-red-400 mb-2">&gt; 2000</div>
                <p class="text-sm text-slate-500">PPM - Berbahaya</p>
            </div>
        </div>

        <!-- Update Info -->
        <div class="text-center animate-slide-up" style="animation-delay: 0.3s;">
            <div class="inline-flex items-center space-x-3 px-6 py-3 bg-slate-900/50 border border-cyan-500/30 rounded-full shadow-lg shadow-cyan-500/10">
                <span class="relative flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-cyan-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-cyan-500"></span>
                </span>
                <span class="text-sm text-slate-400 font-medium">Data diperbarui setiap <span class="text-cyan-400 font-semibold">5 detik</span> dari ThingSpeak</span>
            </div>
        </div>
    </div>

    <script>
        // Mapping kategori dengan warna baru (REMOVED code 2 'Normal')
        const kategoriMap = {
            1: { 
                text: "Baik", 
                classes: "bg-emerald-500/10 text-emerald-400 border-emerald-500/50 shadow-lg shadow-emerald-500/20", 
                dotClass: "bg-emerald-400" 
            },
            3: { 
                text: "Kurang Baik", 
                classes: "bg-yellow-500/10 text-yellow-400 border-yellow-500/50 shadow-lg shadow-yellow-500/20", 
                dotClass: "bg-yellow-400" 
            },
            4: { 
                text: "Buruk", 
                classes: "bg-orange-500/10 text-orange-400 border-orange-500/50 shadow-lg shadow-orange-500/20", 
                dotClass: "bg-orange-400" 
            },
            5: { 
                text: "Bahaya", 
                classes: "bg-red-500/10 text-red-400 border-red-500/50 shadow-lg shadow-red-500/20", 
                dotClass: "bg-red-400" 
            }
        };

        // Mapping arah
        const arahMap = {
            1: { 
                text: "Naik", 
                classes: "bg-red-500/10 text-red-400 border-red-500/50 shadow-lg shadow-red-500/20", 
                icon: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 15l7-7 7 7"></path></svg>' 
            },
            0: { 
                text: "Stabil", 
                classes: "bg-cyan-500/10 text-cyan-400 border-cyan-500/50 shadow-lg shadow-cyan-500/20", 
                icon: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 12h14"></path></svg>' 
            },
            '-1': { 
                text: "Turun", 
                classes: "bg-emerald-500/10 text-emerald-400 border-emerald-500/50 shadow-lg shadow-emerald-500/20", 
                icon: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>' 
            }
        };

        // AJAX Update Function - Every 5 seconds
        async function updateData() {
            try {
                const response = await fetch("{{ route('iot.data') }}");
                const data = await response.json();
                
                // Update PPM dengan smooth transition
                const ppmDisplay = document.getElementById('ppm-display');
                ppmDisplay.style.opacity = '0.5';
                setTimeout(() => {
                    ppmDisplay.textContent = data.ppm;
                    ppmDisplay.style.opacity = '1';
                }, 200);
                
                // Update Status Badge
                const kategoriInfo = kategoriMap[data.kategori];
                if (kategoriInfo) {
                    const statusBadge = document.getElementById('status-badge');
                    statusBadge.className = 'px-6 py-3 rounded-xl border-2 font-semibold text-lg value-transition ' + kategoriInfo.classes;
                    
                    const statusDot = statusBadge.querySelector('.w-2\\.5');
                    if (statusDot) {
                        statusDot.className = 'w-2.5 h-2.5 rounded-full animate-pulse ' + kategoriInfo.dotClass;
                    }
                    
                    const kategoriText = document.getElementById('kategori-text');
                    if (kategoriText) {
                        kategoriText.textContent = kategoriInfo.text;
                    }
                }
                
                // Update Trend Badge
                const arahInfo = arahMap[data.arah];
                if (arahInfo) {
                    const trendBadge = document.getElementById('trend-badge');
                    trendBadge.className = 'px-6 py-3 rounded-xl border-2 font-semibold text-lg value-transition ' + arahInfo.classes;
                    trendBadge.innerHTML = '<span class="flex items-center space-x-2">' + arahInfo.icon + '<span id="arah-text" class="uppercase tracking-wide">' + arahInfo.text + '</span></span>';
                }
                
            } catch (error) {
                console.error('Error fetching data:', error);
            }
        }

        // Auto-update setiap 5 detik
        setInterval(updateData, 5000);
        
        // Initial update after 1 second
        setTimeout(updateData, 1000);
    </script>
</body>
</html>