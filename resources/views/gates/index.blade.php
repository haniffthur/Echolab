@extends('layouts.app')

@section('content')
<h1 class="h3 mb-4 text-gray-800">Manajemen Gate</h1>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Gate</h6>
    </div>
    <div class="card-body">
        <a href="{{ route('gates.create') }}" class="btn btn-primary btn-sm mb-3">
            <i class="fas fa-plus"></i> Tambah Gate Baru
        </a>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Gate</th>
                        <th>Lokasi</th>
                        <th>NomorTerminal</th>
                       
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($gates as $gate)
                        <tr>
                            <td>{{ $loop->iteration + $gates->firstItem() - 1 }}</td>
                            <td>{{ $gate->name }}</td>
                            <td>{{ $gate->location }}</td>
                            <td><span class="badge badge-info">{{ $gate->termno }}</span></td>
                            
                            <td>
                                @if($gate->status == 'active')
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-danger">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('gates.edit', $gate->id) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('gates.destroy', $gate->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Belum ada data.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-end">
            {{ $gates->links() }}
        </div>
    </div>
</div>
@endsection