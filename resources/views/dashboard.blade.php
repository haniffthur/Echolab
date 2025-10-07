@extends('layouts.app')

@push('styles')
<style>
    .avatar-circle { width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; }
    .avatar-employee { background-color: #e8edff; color: #4e73df; }
    .avatar-guest { background-color: #e2f7f0; color: #1cc88a; }
    .live-feed-table tbody tr { animation: fadeIn 0.5s ease-in-out; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
</style>
@endpush

@section('content')
    <h1 class="h3 mb-4 text-gray-800">{{ __('Dasbor Monitoring Real-time') }}</h1>

    {{-- Bagian HTML untuk Kartu Statistik dan Daftar Nama (tidak ada perubahan) --}}
    <div class="row">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body"><div class="row no-gutters align-items-center"><div class="col mr-2"><div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Karyawan di Dalam</div><div id="employee-count" class="h5 mb-0 font-weight-bold text-gray-800">{{ $employeeInsideCount }} Orang</div></div><div class="col-auto"><i class="fas fa-users fa-2x text-gray-300"></i></div></div></div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body"><div class="row no-gutters align-items-center"><div class="col mr-2"><div class="text-xs font-weight-bold text-success text-uppercase mb-1">Tamu di Dalam</div><div id="guest-count" class="h5 mb-0 font-weight-bold text-gray-800">{{ $guestInsideCount }} Orang</div></div><div class="col-auto"><i class="fas fa-user-tie fa-2x text-gray-300"></i></div></div></div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body"><div class="row no-gutters align-items-center"><div class="col mr-2"><div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total di Dalam Gedung</div><div id="total-count" class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalInsideCount }} Orang</div></div><div class="col-auto"><i class="fas fa-building fa-2x text-gray-300"></i></div></div></div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-users mr-2"></i>Daftar Karyawan di Dalam</h6></div>
                <div class="card-body" style="min-height: 400px; max-height: 400px; overflow-y: auto;">
                    <div class="table-responsive"><table class="table table-hover" width="100%" cellspacing="0"><tbody id="employee-feed-body">
                        @forelse ($employeesInside as $employee)
                            <tr><td style="width: 50px;"><div class="avatar-circle avatar-employee"><i class="{{ $employee['icon'] }}"></i></div></td><td class="align-middle"><div class="font-weight-bold">{{ $employee['name'] }}</div><div class="small text-gray-500">{{ $employee['detail'] }}</div></td><td class="align-middle text-right font-weight-bold">{{ $employee['time_in'] }}</td></tr>
                        @empty
                            <tr><td colspan="3" class="text-center py-5 text-gray-500">Tidak ada karyawan di dalam.</td></tr>
                        @endforelse
                    </tbody></table></div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-success"><i class="fas fa-user-tie mr-2"></i>Daftar Tamu di Dalam</h6></div>
                <div class="card-body" style="min-height: 400px; max-height: 400px; overflow-y: auto;">
                     <div class="table-responsive"><table class="table table-hover" width="100%" cellspacing="0"><tbody id="guest-feed-body">
                        @forelse ($guestsInside as $guest)
                            <tr><td style="width: 50px;"><div class="avatar-circle avatar-guest"><i class="{{ $guest['icon'] }}"></i></div></td><td class="align-middle"><div class="font-weight-bold">{{ $guest['name'] }}</div><div class="small text-gray-500">{{ $guest['detail'] }}</div></td><td class="align-middle text-right font-weight-bold">{{ $guest['time_in'] }}</td></tr>
                        @empty
                            <tr><td colspan="3" class="text-center py-5 text-gray-500">Tidak ada tamu di dalam.</td></tr>
                        @endforelse
                    </tbody></table></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function updateDashboardUI(data) {
        document.getElementById('employee-count').innerText = data.employeeInsideCount + ' Orang';
        document.getElementById('guest-count').innerText = data.guestInsideCount + ' Orang';
        document.getElementById('total-count').innerText = data.totalInsideCount + ' Orang';

        const employeeBody = document.getElementById('employee-feed-body');
        const guestBody = document.getElementById('guest-feed-body');

        let newEmployeeHtml = '';
        if (data.employeesInside.length > 0) {
            data.employeesInside.forEach(employee => {
                let row = `<tr style="animation: fadeIn 0.5s ease-in-out;">
                    <td style="width: 50px;"><div class="avatar-circle avatar-employee"><i class="${employee.icon}"></i></div></td>
                    <td class="align-middle"><div class="font-weight-bold">${employee.name}</div><div class="small text-gray-500">${employee.detail}</div></td>
                    <td class="align-middle text-right font-weight-bold">${employee.time_in}</td> 
                </tr>`; // <-- PERBAIKAN DI SINI: </div> diubah menjadi </td>
                newEmployeeHtml += row;
            });
        } else {
            newEmployeeHtml = '<tr><td colspan="3" class="text-center py-5 text-gray-500">Tidak ada karyawan di dalam.</td></tr>';
        }

        let newGuestHtml = '';
        if (data.guestsInside.length > 0) {
            data.guestsInside.forEach(guest => {
                let row = `<tr style="animation: fadeIn 0.5s ease-in-out;">
                    <td style="width: 50px;"><div class="avatar-circle avatar-guest"><i class="${guest.icon}"></i></div></td>
                    <td class="align-middle"><div class="font-weight-bold">${guest.name}</div><div class="small text-gray-500">${guest.detail}</div></td>
                    <td class="align-middle text-right font-weight-bold">${guest.time_in}</td>
                </tr>`;
                newGuestHtml += row;
            });
        } else {
            newGuestHtml = '<tr><td colspan="3" class="text-center py-5 text-gray-500">Tidak ada tamu di dalam.</td></tr>';
        }

        if (employeeBody.innerHTML !== newEmployeeHtml) {
            employeeBody.innerHTML = newEmployeeHtml;
        }
        if (guestBody.innerHTML !== newGuestHtml) {
            guestBody.innerHTML = newGuestHtml;
        }
    }

    function refreshDashboard() {
        fetch("{{ route('api.dashboard.data') }}")
            .then(response => response.json())
            .then(data => {
                updateDashboardUI(data);
            })
            .catch(error => console.error('Error fetching dashboard data:', error));
    }

    document.addEventListener('DOMContentLoaded', function() {
        refreshDashboard();
    });

    setInterval(refreshDashboard, 2000);
</script>
@endpush