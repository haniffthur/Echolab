@extends('layouts.app')

@section('content')
<h1 class="h3 mb-4 text-gray-800">Daftarkan Kartu Baru</h1>

<div class="card shadow mb-4">
    <div class="card-body">
        <form action="{{ route('cards.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="cardno">Nomor Kartu (cardno)</label>
                <input type="text" class="form-control @error('cardno') is-invalid @enderror" id="cardno" name="cardno" value="{{ old('cardno') }}" placeholder="Tempelkan kartu pada card reader...">
                @error('cardno') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- BAGIAN PENTING 1: Pastikan blok HTML ini ada --}}
            <div class="form-group">
                <label>Tipe Kartu</label>
                <div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="type_employee" name="type" class="custom-control-input" value="employee" {{ old('type', 'employee') == 'employee' ? 'checked' : '' }}>
                        <label class="custom-control-label" for="type_employee">Karyawan</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="type_guest" name="type" class="custom-control-input" value="guest" {{ old('type') == 'guest' ? 'checked' : '' }}>
                        <label class="custom-control-label" for="type_guest">Tamu</label>
                    </div>
                </div>
            </div>

            <div class="form-group" id="employee-field">
                <label for="cardable_id">Tugaskan ke Karyawan</label>
                <select class="form-control @error('cardable_id') is-invalid @enderror" id="cardable_id" name="cardable_id">
                    <option value="">-- Pilih Karyawan --</option>
                    @foreach ($employees as $employee)
                        <option value="{{ $employee->id }}" {{ old('cardable_id') == $employee->id ? 'selected' : '' }}>
                            {{ $employee->name }} ({{ $employee->employee_id }})
                        </option>
                    @endforeach
                </select>
                @error('cardable_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="is_active">Status Kartu</label>
                <select class="form-control @error('is_active') is-invalid @enderror" id="is_active" name="is_active">
                    <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Non-Aktif</option>
                </select>
                @error('is_active') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('cards.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const employeeField = document.getElementById('employee-field');
        const employeeSelect = document.getElementById('cardable_id');
        const radios = document.querySelectorAll('input[name="type"]');

        function toggleEmployeeField() {
            // Cek radio button mana yang dipilih
            const selectedType = document.querySelector('input[name="type"]:checked').value;
            
            if (selectedType === 'employee') {
                employeeField.style.display = 'block'; // Tampilkan
                employeeSelect.disabled = false; // Aktifkan dropdown
            } else {
                employeeField.style.display = 'none'; // Sembunyikan
                employeeSelect.disabled = true; // Non-aktifkan dropdown
            }
        }

        radios.forEach(radio => radio.addEventListener('change', toggleEmployeeField));
        
        // Panggil saat halaman dimuat untuk set kondisi awal
        toggleEmployeeField();
        
        // Inisialisasi TomSelect
        new TomSelect("#cardable_id",{ create: false });
    });
</script>
@endpush