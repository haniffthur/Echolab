@extends('layouts.app')

@section('content')
<h1 class="h3 mb-4 text-gray-800">Laporan Log Tap Pintu (Real-time)</h1>

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Riwayat Aktivitas Pintu</h6>
        <div class="text-xs">
            <span class="mr-2">
                <i class="fas fa-circle text-gray-500"></i> Auto-refresh setiap 10 detik
            </span>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Waktu Tap</th>
                        <th>Nama</th>
                        <th>NIP</th>
                        <th>Nomor Kartu</th>
                        <th>Gate</th>
                        <th>Tipe</th>
                    </tr>
                </thead>
                {{-- Beri ID agar bisa dimanipulasi JavaScript --}}
                <tbody id="log-table-body">
                    {{-- Konten awal akan diisi oleh AJAX --}}
                    <tr>
                        <td colspan="6" class="text-center">Memuat data...</td>
                    </tr>
                </tbody>
            </table>
        </div>
        {{-- Kita hapus paginasi karena sekarang datanya real-time --}}
    </div>
</div>
@endsection

@push('scripts')
<script>
    function refreshLogTable() {
        fetch("{{ route('api.access-logs.data') }}")
            .then(response => response.json())
            .then(data => {
                const logBody = document.getElementById('log-table-body');
                logBody.innerHTML = ''; // Kosongkan tabel

                if (data.length > 0) {
                    data.forEach(log => {
                        let typeBadge = log.type === 'in'
                            ? '<span class="badge badge-success">Masuk</span>'
                            : '<span class="badge badge-danger">Keluar</span>';

                        let row = `<tr>
                            <td>${log.time}</td>
                            <td>${log.user_name}</td>
                            <td>${log.user_nip}</td>
                            <td>${log.card_no}</td>
                            <td>${log.gate_name}</td>
                            <td>${typeBadge}</td>
                        </tr>`;
                        logBody.innerHTML += row;
                    });
                } else {
                    logBody.innerHTML = '<tr><td colspan="6" class="text-center">Belum ada aktivitas tap yang terekam.</td></tr>';
                }
            })
            .catch(error => console.error('Error fetching log data:', error));
    }

    // Panggil fungsi pertama kali saat halaman dimuat
    document.addEventListener('DOMContentLoaded', function() {
        refreshLogTable();
    });

    // Jalankan fungsi refresh setiap 10 detik
    setInterval(refreshLogTable, 1000);
</script>
@endpush