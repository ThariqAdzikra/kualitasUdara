<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF--8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoring Kualitas Udara - Real-time</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
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

        .main-display {
            background: rgba(15, 20, 35, 0.6);
            backdrop-filter: blur(20px);
            border-radius: 30px;
            padding: 80px 60px;
            margin-bottom: 40px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .main-display::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(102, 126, 234, 0.1) 0%, transparent 70%);
            animation: rotate 20s linear infinite;
        }

        .label {
            font-size: 16px;
            color: #a0aec0;
            text-transform: uppercase;
            letter-spacing: 3px;
            margin-bottom: 20px;
            font-weight: 600;
            position: relative;
            z-index: 1;
        }

        .digital-display {
            font-family: 'Orbitron', monospace;
            font-size: 140px;
            font-weight: 900;
            letter-spacing: 8px;
            position: relative;
            z-index: 1;
            text-shadow: 0 0 7px rgba(220, 38, 38, 0.9), 0 0 25px rgba(220, 38, 38, 0.5), 0 0 55px rgba(220, 38, 38, 0.2);
        }

        .unit {
            font-size: 40px;
            color: #a0aec0;
            font-weight: 400;
            margin-left: 10px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-top: 40px;
        }

        .info-card {
            background: rgba(15, 20, 35, 0.6);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 40px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .info-card:hover {
            transform: translateY(-5px);
            border-color: rgba(102, 126, 234, 0.3);
            box-shadow: 0 20px 40px rgba(102, 126, 234, 0.2);
        }

        .info-card h3 {
            font-size: 16px;
            color: #a0aec0;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .info-value {
            font-family: 'Orbitron', monospace;
            font-size: 48px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .status-badge {
            display: inline-block;
            padding: 12px 30px;
            border-radius: 50px;
            font-size: 20px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-top: 10px;
        }

        .trend-indicator {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 12px 30px;
            border-radius: 50px;
            font-size: 20px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-top: 10px;
        }

        .arrow {
            font-size: 20px;
            font-weight: 900;
        }

        .update-info {
            text-align: center;
            margin-top: 40px;
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

        .live-indicator {
            width: 12px;
            height: 12px;
            background: #10b981;
            border-radius: 50%;
            animation: blink 2s ease-in-out infinite;
        }
        
        /* === Kategori & Status Styling === */
        .digital-display.baik { color: #10b981; }
        .digital-display.normal { color: #f59e0b; }
        .digital-display.kurang-baik { color: #ef4444; }
        .digital-display.buruk { color: #dc2626; }
        .digital-display.bahaya {
            color: #8b0000;
            text-shadow: 0 0 10px #ff0000, 0 0 20px #ff0000;
        }

        .status-badge.baik { background: rgba(16, 185, 129, 0.2); color: #10b981; border: 2px solid #10b981; }
        .status-badge.normal { background: rgba(245, 158, 11, 0.2); color: #f59e0b; border: 2px solid #f59e0b; }
        .status-badge.kurang-baik { background: rgba(239, 68, 68, 0.2); color: #ef4444; border: 2px solid #ef4444; }
        .status-badge.buruk { background: rgba(220, 38, 38, 0.2); color: #dc2626; border: 2px solid #dc2626; }
        .status-badge.bahaya { background: rgba(139, 0, 0, 0.3); color: #ff4d4d; border: 2px solid #8b0000; animation: pulse 1s ease-in-out infinite; }

        .trend-indicator.naik { background: rgba(239, 68, 68, 0.2); color: #ef4444; border: 2px solid #ef4444; }
        .trend-indicator.turun { background: rgba(16, 185, 129, 0.2); color: #10b981; border: 2px solid #10b981; }
        .trend-indicator.stabil { background: rgba(59, 130, 246, 0.2); color: #3b82f6; border: 2px solid #3b82f6; }

        /* === Animations === */
        @keyframes bgPulse {
            0%, 100% { opacity: 0.3; }
            50% { opacity: 0.6; }
        }

        @keyframes rotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
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
            .digital-display {
                font-size: 80px;
            }
            .main-display {
                padding: 40px 30px;
            }
            .info-value {
                font-size: 36px;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <div class="logo">AirQuality Monitor</div>
            <div class="nav-links">
                <a href="{{ route('iot.index') }}" class="active">Real-time</a>
                <a href="{{ route('iot.dashboard') }}">Dashboard</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="header">
            <h1>Monitoring Kualitas Udara</h1>
            <p>Pemantauan Real-time Kadar Polutan Udara (CO2)</p>
        </div>

        <div class="main-display">
            <div class="label">Kadar PPM (Parts Per Million)</div>
            <div class="digital-display {{ strtolower(str_replace(' ', '-', $kategori)) }}" id="ppm-display">
                <span id="ppm-value">{{ number_format($ppm, 2) }}</span><span class="unit">PPM</span>
            </div>
        </div>

        <div class="info-grid">
            <div class="info-card">
                <h3>Status Kualitas</h3>
                <div class="status-badge {{ strtolower(str_replace(' ', '-', $kategori)) }}" id="status-display">
                    {{ $kategori }}
                </div>
            </div>

            <div class="info-card">
                <h3>Tren Perubahan</h3>
                <div class="trend-indicator {{ strtolower($arah) }}" id="trend-display">
                    <span class="arrow">
                        @if($arahCode == 1) ↑ @elseif($arahCode == -1) ↓ @else → @endif
                    </span>
                    <span>{{ $arah }}</span>
                </div>
            </div>
        </div>

        <div class="update-info">
            <p>
                <span class="live-indicator"></span>
                Data diperbarui setiap 20 detik dari ThingSpeak
            </p>
        </div>
    </div>

    <script>
        setInterval(async function() {
            try {
                const response = await fetch('{{ route("iot.data") }}');
                if (!response.ok) throw new Error('Network response was not ok');
                const data = await response.json();
                
                document.getElementById('ppm-value').textContent = data.ppm;
                
                const kategoriMap = {
                    1: { text: 'Baik', class: 'baik' },
                    2: { text: 'Normal', class: 'normal' },
                    3: { text: 'Kurang Baik', class: 'kurang-baik' },
                    4: { text: 'Buruk', class: 'buruk' },
                    5: { text: 'Bahaya', class: 'bahaya' }
                };
                
                const kategori = kategoriMap[data.kategori] || kategoriMap[1];
                const statusDisplay = document.getElementById('status-display');
                statusDisplay.textContent = kategori.text;
                statusDisplay.className = 'status-badge ' + kategori.class;
                
                const ppmDisplay = document.getElementById('ppm-display');
                ppmDisplay.className = 'digital-display ' + kategori.class;
                
                const arahMap = {
                    '1': { text: 'Naik', class: 'naik', arrow: '↑' },
                    '0': { text: 'Stabil', class: 'stabil', arrow: '→' },
                    '-1': { text: 'Turun', class: 'turun', arrow: '↓' }
                };
                
                const arah = arahMap[data.arah.toString()] || arahMap['0'];
                const trendDisplay = document.getElementById('trend-display');
                trendDisplay.innerHTML = `<span class="arrow">${arah.arrow}</span><span>${arah.text}</span>`;
                trendDisplay.className = 'trend-indicator ' + arah.class;
                
            } catch (error) {
                console.error('Error updating data:', error);
            }
        }, 20000); // Disesuaikan menjadi 20 detik seperti di Arduino
    </script>
</body>
</html>