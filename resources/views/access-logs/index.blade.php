@extends('layouts.app')

@push('styles')
{{-- Library untuk date picker --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush

@section('content')
<h1 class="h3 mb-4 text-gray-800">Laporan Log Tap Pintu</h1>

<!-- Form Filter -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form action="{{ route('access-logs.index') }}" method="GET" class="form-inline">
            <div class="form-group mb-2 mr-sm-2">
                <label for="start_date" class="sr-only">Tanggal Mulai</label>
                <input type="text" class="form-control" id="start_date" name="start_date" placeholder="Tanggal Mulai" value="{{ $startDate ?? '' }}">
            </div>
            <div class="form-group mb-2 mr-sm-2">
                <label for="end_date" class="sr-only">Tanggal Selesai</label>
                <input type="text" class="form-control" id="end_date" name="end_date" placeholder="Tanggal Selesai" value="{{ $endDate ?? '' }}">
            </div>
            <button type="submit" class="btn btn-primary mb-2 mr-2"><i class="fas fa-filter mr-1"></i> Filter</button>
            <a href="{{ route('access-logs.index') }}" class="btn btn-secondary mb-2">Reset</a>
        </form>
    </div>
</div>


<div class="card shadow-sm">
    <div class="card-header bg-white border-0 py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Riwayat Aktivitas Pintu</h6>
        
        {{-- Tombol Ekspor --}}
        <a href="{{ route('access-logs.export', request()->query()) }}" class="btn btn-sm btn-success shadow-sm">
            <i class="fas fa-file-excel fa-sm text-white-50"></i> Ekspor ke Excel
        </a>
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
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($accessLogs as $log)
                        <tr>
                            <td>{{ $log->tap_time->format('d M Y, H:i:s') }}</td>
                            <td>{{ $log->userable->name ?? 'Tamu' }}</td>
                            <td>{{ $log->userable->employee_id ?? '-' }}</td>
                            <td>{{ $log->card->cardno ?? 'N/A' }}</td>
                            <td>{{ $log->gate->name ?? 'N/A' }}</td>
                            <td>
                                @if($log->type == 'in')
                                    <span class="badge badge-success">Masuk</span>
                                @else
                                    <span class="badge badge-danger">Keluar</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada data yang ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Paginasi yang mendukung filter --}}
        <div class="d-flex justify-content-end">
            {{ $accessLogs->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- Skrip untuk date picker --}}
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    flatpickr("#start_date", {
        dateFormat: "Y-m-d",
    });
    flatpickr("#end_date", {
        dateFormat: "Y-m-d",
    });
</script>
@endpush
