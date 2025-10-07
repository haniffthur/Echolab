<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Live Activity Monitor - VMS</title>

    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">
    
    <style>
        body {
            background-color: #f8f9fc;
            font-family: 'Inter', sans-serif;
        }
        .main-header {
            padding: 2rem 0;
        }
        .main-title {
            font-size: 1.5rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #3a3b45;
        }
        .digital-clock {
            font-size: 3rem;
            font-weight: 700;
            color: #5a5c69;
        }
        .digital-date {
            font-size: 1rem;
            color: #858796;
        }
        .stat-card .card-body {
            padding: 1.5rem;
        }
        .table-monitor thead th {
            border: none;
            background-color: #f8f9fc;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.8px;
            color: #212529; /* Warna Hitam untuk Header */
            font-weight: 700; /* Bold */
            padding: 1rem 1.5rem;
        }
        .table-monitor tbody tr {
            border-bottom: 1px solid #eaecf4;
            animation: fadeIn 0.4s ease-in-out;
        }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        .table-monitor tbody tr:last-child {
            border-bottom: none;
        }
        .table-monitor td {
            vertical-align: middle;
            padding: 1.25rem 1.5rem;
            border: none;
            font-weight: 500;
            color: #5a5c69;
        }
        .table-monitor .time-column {
            font-weight: 600;
            color: #3a3b45;
        }
        .card-table-container {
            height: calc(100vh - 400px); /* Disesuaikan tingginya */
            min-height: 400px;
            overflow-y: auto;
        }
        .status-badge {
            font-size: 0.8rem; /* Ukuran font badge dibesarkan */
            padding: 0.5em 0.75em;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container-fluid pt-4">

        <!-- Header: Title & Clock -->
        <div class="main-header text-center">
            <h1 class="main-title">Live Activity Monitor</h1>
            <div id="clock" class="digital-clock"></div>
            <div id="date" class="digital-date"></div>
        </div>

        <!-- Row: Stat Cards -->
        <div class="row mb-4">
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-info shadow-sm h-100 stat-card">
                    <div class="card-body"><div class="row no-gutters align-items-center"><div class="col mr-2"><div class="text-m font-weight-bold text-info text-uppercase mb-1">Total Inside</div><div id="total-count" class="h4 mb-0 font-weight-bold text-gray-800">0</div></div><div class="col-auto"><i class="fas fa-building fa-2x text-gray-300"></i></div></div></div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-primary shadow-sm h-100 stat-card">
                    <div class="card-body"><div class="row no-gutters align-items-center"><div class="col mr-2"><div class="text-m font-weight-bold text-primary text-uppercase mb-1">Employees Inside</div><div id="employee-count" class="h4 mb-0 font-weight-bold text-gray-800">0</div></div><div class="col-auto"><i class="fas fa-users fa-2x text-gray-300"></i></div></div></div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-success shadow-sm h-100 stat-card">
                    <div class="card-body"><div class="row no-gutters align-items-center"><div class="col mr-2"><div class="text-m font-weight-bold text-success text-uppercase mb-1">Guests Inside</div><div id="guest-count" class="h4 mb-0 font-weight-bold text-gray-800">0</div></div><div class="col-auto"><i class="fas fa-user-tie fa-2x text-gray-300"></i></div></div></div>
                </div>
            </div>
        </div>

        <!-- Monitor Table -->
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive card-table-container">
                    <table class="table table-hover table-monitor mb-0" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th class="pl-4" style="width: 5%;">No</th>
                                <th style="width: 20%;">Date & Time</th>
                                <th>Name</th>
                                <th>Card Number</th>
                                <th>Gate</th>
                                <th class="pr-4 text-center" style="width: 10%;">Status</th>
                            </tr>
                        </thead>
                        <tbody id="monitor-table-body">
                            <tr><td colspan="6" class="text-center py-5 text-gray-500">Loading data...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        function updateStats(data) {
            document.getElementById('total-count').innerText = data.totalInsideCount;
            document.getElementById('employee-count').innerText = data.employeeInsideCount;
            document.getElementById('guest-count').innerText = data.guestInsideCount;
        }

        function updateActivityTable(data) {
            const monitorBody = document.getElementById('monitor-table-body');
            let newHtml = '';
            let counter = 1;

            if (data && data.length > 0) {
                data.forEach(log => {
                    let statusHtml = log.type === 'in'
                        ? '<span class="badge badge-success status-badge"><i class="fas fa-arrow-down mr-1"></i>In</span>'
                        : '<span class="badge badge-danger status-badge"><i class="fas fa-arrow-up mr-1"></i>Out</span>';

                    newHtml += `
                        <tr>
                            <td class="pl-4 font-weight-bold text-gray-500">${String(counter++).padStart(2, '0')}</td>
                            <td class="time-column">${log.time}</td>
                            <td>${log.user_name}</td>
                            <td class="text-muted">${log.card_no}</td>
                            <td>${log.gate_name}</td>
                            <td class="pr-4 text-center">${statusHtml}</td>
                        </tr>`;
                });
            } else {
                newHtml = '<tr><td colspan="6" class="text-center py-5 text-gray-500">No tap activity recorded yet.</td></tr>';
            }
            
            if (monitorBody.innerHTML !== newHtml) {
                monitorBody.innerHTML = newHtml;
            }
        }
        
        function updateClock() {
            const now = new Date();
            document.getElementById('clock').innerText = now.toLocaleTimeString('en-GB', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            document.getElementById('date').innerText = now.toLocaleDateString('en-GB', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
        }

        function refreshData() {
            fetch("{{ route('api.dashboard.data') }}").then(r => r.json()).then(updateStats);
            fetch("{{ route('api.access-logs.data') }}").then(r => r.json()).then(updateActivityTable);
        }

        document.addEventListener('DOMContentLoaded', refreshData);
        setInterval(refreshData, 1000);
        setInterval(updateClock, 1000);
    </script>
</body>
</html>

