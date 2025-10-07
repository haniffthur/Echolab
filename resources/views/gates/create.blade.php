@extends('layouts.app')

@section('content')
    <h1 class="h3 mb-4 text-gray-800">Tambah Gate Baru</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Tambah Gate</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('gates.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="name">Nama Gate</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                        value="{{ old('name') }}">
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label for="location">Lokasi</label>
                    <input type="text" class="form-control @error('location') is-invalid @enderror" id="location"
                        name="location" value="{{ old('location') }}">
                    @error('location') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label for="termno">Nomor Terminal (termno)</label>
                    <input type="text" class="form-control @error('termno') is-invalid @enderror" id="termno" name="termno"
                        value="{{ old('termno', $gate->termno ?? '') }}">
                    @error('termno') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <select class="form-control @error('status') is-invalid @enderror" id="status" name="status">
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('gates.index') }}" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
@endsection