<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Live Building Monitor - VMS</title>

    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    {{-- BARIS INI DIHAPUS untuk memastikan 100% offline --}}
    {{-- <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"> --}}
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">
    
    <style>
        body { 
            background-color: #f8f9fc; 
            /* PERUBAHAN: Menggunakan font default SB Admin 2 */
            font-family: "Nunito", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
            overflow-y: hidden; 
        }
        .header-section { border-bottom: 1px solid #e3e6f0; }
        .main-title { font-weight: 700; color: #3a3b45; }
        .clock-display .time { font-size: 2rem; font-weight: 700; color: #3a3b45; }
        .clock-display .date { font-size: 0.9rem; color: #858796; }
        .stat-display .label { text-transform: uppercase; font-size: 0.75rem; font-weight: 700; letter-spacing: 0.5px; }
        .stat-display .count { font-size: 2rem; font-weight: 700; line-height: 1; }
        .feed-container { height: calc(100vh - 120px); overflow-y: auto; }
        .feed-header { font-size: 1.1rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; padding-bottom: 1rem; border-bottom: 2px solid; }
        .feed-list .list-group-item { background-color: #fff; border: 1px solid #e3e6f0; border-radius: 0.5rem; margin-bottom: 10px; animation: popIn 0.5s ease-out; box-shadow: 0 2px 10px rgba(0,0,0,0.03); }
        @keyframes popIn { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }
        .avatar-circle { width: 45px; height: 45px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; }
        .avatar-employee { background-color: #e8edff; color: #4e73df; }
        .avatar-guest { background-color: #e2f7f0; color: #1cc88a; }
        .person-name { font-size: 1rem; color: #3a3b45; font-weight: 600; }
        .person-detail { font-size: 0.8rem; }
        .time-display { font-size: 0.9rem; font-weight: 600; }
        .feed-container::-webkit-scrollbar { width: 8px; }
        .feed-container::-webkit-scrollbar-track { background: transparent; }
        .feed-container::-webkit-scrollbar-thumb { background: #d1d3e2; border-radius: 4px; }
    </style>
</head>
<body>
    <div class="container-fluid vh-100 d-flex flex-column p-4">

        <!-- Header: Judul, Statistik, dan Jam -->
        <header class="header-section pb-3 mb-4">
            <div class="row align-items-center">
                <div class="col-md-3">
                    <h2 class="main-title m-0"><i class="fas fa-desktop mr-2"></i>Live Monitor</h2>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col stat-display text-center"><div class="label text-primary">Karyawan</div><div id="employee-count" class="count text-primary">0</div></div>
                        <div class="col stat-display text-center"><div class="label text-success">Tamu</div><div id="guest-count" class="count text-success">0</div></div>
                        <div class="col stat-display text-center"><div class="label text-info">Total</div><div id="total-count" class="count text-info">0</div></div>
                    </div>
                </div>
                <div class="col-md-3 text-right clock-display">
                    <div id="clock" class="time"></div>
                    <div id="date" class="date"></div>
                </div>
            </div>
        </header>

        <!-- Konten: Dua Kolom -->
        <div class="row flex-grow-1" style="min-height: 0;">
            <!-- Kolom Kiri: Karyawan -->
            <div class="col-md-6 d-flex flex-column">
                <h4 class="feed-header border-primary">Karyawan di Dalam</h4>
                <div class="feed-container pr-3"><ul class="list-group list-group-flush" id="employee-feed-body"><li class="list-group-item text-center text-gray-500 py-5">Memuat data...</li></ul></div>
            </div>
            <!-- Kolom Kanan: Tamu -->
            <div class="col-md-6 d-flex flex-column">
                <h4 class="feed-header border-success">Tamu di Dalam</h4>
                 <div class="feed-container pr-3"><ul class="list-group list-group-flush" id="guest-feed-body"><li class="list-group-item text-center text-gray-500 py-5">Memuat data...</li></ul></div>
            </div>
        </div>
    </div>

    <script>
        function updateMonitorUI(data) {
            document.getElementById('employee-count').innerText = data.employeeInsideCount;
            document.getElementById('guest-count').innerText = data.guestInsideCount;
            document.getElementById('total-count').innerText = data.totalInsideCount;
            const employeeBody = document.getElementById('employee-feed-body');
            const guestBody = document.getElementById('guest-feed-body');

            let employeeHtml = '';
            if (data.employeesInside.length > 0) {
                data.employeesInside.forEach(p => {
                    employeeHtml += `<li class="list-group-item d-flex justify-content-between align-items-center"><div class="d-flex align-items-center"><div class="avatar-circle avatar-employee mr-3"><i class="${p.icon}"></i></div><div><div class="person-name">${p.name}</div><div class="small text-gray-500 person-detail">${p.detail} <span class="mx-1">&bull;</span> <i class="fas fa-door-open fa-xs mr-1"></i> ${p.gate_name}</div></div></div><div class="text-right"><div class="font-weight-bold text-gray-800 time-display"><i class="far fa-clock mr-1"></i>${p.time_in}</div></div></li>`;
                });
            } else { employeeHtml = '<li class="list-group-item text-center text-gray-500 py-5">Tidak ada karyawan di dalam.</li>'; }
            
            let guestHtml = '';
            if (data.guestsInside.length > 0) {
                data.guestsInside.forEach(p => {
                    guestHtml += `<li class="list-group-item d-flex justify-content-between align-items-center"><div class="d-flex align-items-center"><div class="avatar-circle avatar-guest mr-3"><i class="${p.icon}"></i></div><div><div class="person-name">${p.name}</div><div class="small text-gray-500 person-detail">${p.detail} <span class="mx-1">&bull;</span> <i class="fas fa-door-open fa-xs mr-1"></i> ${p.gate_name}</div></div></div><div class="text-right"><div class="font-weight-bold text-gray-800 time-display"><i class="far fa-clock mr-1"></i>${p.time_in}</div></div></li>`;
                });
            } else { guestHtml = '<li class="list-group-item text-center text-gray-500 py-5">Tidak ada tamu di dalam.</li>'; }
            
            if (employeeBody.innerHTML !== employeeHtml) { employeeBody.innerHTML = employeeHtml; }
            if (guestBody.innerHTML !== guestHtml) { guestBody.innerHTML = guestHtml; }
        }
        
        function updateClock() {
            const now = new Date();
            document.getElementById('clock').innerText = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            document.getElementById('date').innerText = now.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
        }

        function refreshData() {
            fetch("{{ route('api.dashboard.data') }}").then(response => response.json()).then(data => updateMonitorUI(data));
        }

        document.addEventListener('DOMContentLoaded', function() { refreshData(); updateClock(); });
        setInterval(refreshData, 3000);
        setInterval(updateClock, 1000);
    </script>
</body>
</html>

