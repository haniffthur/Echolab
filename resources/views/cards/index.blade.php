@extends('layouts.app')

@section('content')
<h1 class="h3 mb-4 text-gray-800">Manajemen Kartu</h1>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Kartu Akses</h6>
    </div>
    <div class="card-body">
        <a href="{{ route('cards.create') }}" class="btn btn-primary btn-sm mb-3"><i class="fas fa-plus"></i> Daftarkan Kartu Baru</a>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nomor Kartu</th>
                        <th>Tipe</th>
                        <th>Pemilik</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($cards as $card)
                        <tr>
                            <td>{{ $loop->iteration + $cards->firstItem() - 1 }}</td>
                            <td>{{ $card->cardno }}</td>
                            <td>
                                @if($card->type == 'employee')
                                    <span class="badge badge-primary">Karyawan</span>
                                @else
                                    <span class="badge badge-success">Tamu</span>
                                @endif
                            </td>
                            <td>
                                @if ($card->cardable)
                                    {{ $card->cardable->name }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($card->is_active)
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-danger">Non-Aktif</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('cards.edit', $card->id) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('cards.destroy', $card->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus kartu ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada data kartu.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-end">
            {{ $cards->links() }}
        </div>
    </div>
</div>
@endsection