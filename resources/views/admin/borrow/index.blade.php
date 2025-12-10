@extends('templates.app')

@section('content')
<div class="container mt-3">

    @if (Session::get('success'))
        <div class="alert alert-success">
            {{ Session::get('success') }}
        </div>
    @endif

    @if (Session::get('error'))
        <div class="alert alert-danger">
            {{ Session::get('error') }}
        </div>
    @endif

    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('admin.borrowings.export') }}" class="btn btn-secondary me-2">Export (.csv)</a>
        <a href="{{ route('admin.borrowings.trash') }}" class="btn btn-warning ms-2">Data Sampah</a>
        <a href="{{ route('admin.borrowings.create') }}" class="btn btn-success ms-2">Tambah Peminjaman</a>
    </div>

    <h5 class="mt-3">Data Peminjaman & Pengembalian</h5>

    <form method="GET" action="{{ route('admin.borrowings.index') }}" class="d-flex mb-3">
        <input type="text" name="search"
               class="form-control me-2"
               placeholder="Cari nama peminjam atau barang..."
               value="{{ request('search') }}">

        <button type="submit" class="btn btn-primary">Cari</button>

        @if(request('search'))
            <a href="{{ route('admin.borrowings.index') }}"
               class="btn btn-secondary ms-2">
                Reset
            </a>
        @endif
    </form>

    <table class="table table-bordered">
        <thead class="text-center">
            <tr>
                <th>#</th>
                <th>Nama Peminjam</th>
                <th>Barang</th>
                <th>Jumlah</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Kembali</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
            @if(isset($borrowings) && $borrowings->count())
                @foreach ($borrowings as $key => $data)
                    <tr class="text-center">
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $data->user->name ?? 'N/A' }}</td>
                        <td>{{ $data->item->name ?? 'N/A' }}</td>
                        <td>{{ $data->quantity }}</td>
                        <td>{{ $data->borrow_date }}</td>
                        <td>{{ $data->return_date ?? '-' }}</td>

                        <td>
                            @if ($data->status === 'borrowed')
                                <span class="badge bg-warning">Borrowed</span>
                            @elseif ($data->status === 'denda' || $data->status === 'fine')
                                <span class="badge bg-danger">Denda</span>
                            @else
                                <span class="badge bg-success">Returned</span>
                            @endif
                        </td>

                        <td class="d-flex justify-content-center">
                            @if(Route::has('admin.borrowings.return') && $data->status === 'borrowed')
                                <form action="{{ route('admin.borrowings.return', $data->id) }}"
                                      method="POST" class="me-1">
                                    @csrf
                                    @method('PATCH')
                                    <button class="btn btn-success btn-sm"
                                            onclick="return confirm('Yakin kembalikan barang ini?')">
                                        Kembalikan
                                    </button>
                                </form>
                            @endif

                            @if(Route::has('admin.borrowings.edit'))
                                <a href="{{ route('admin.borrowings.edit', $data->id) }}"
                                   class="btn btn-secondary btn-sm me-1">
                                    Edit
                                </a>
                            @endif

                            @if(Route::has('admin.borrowings.delete'))
                                <form action="{{ route('admin.borrowings.delete', $data->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Yakin hapus data?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm">
                                        Hapus
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="8" class="text-center">
                        Tidak ada data peminjaman.
                    </td>
                </tr>
            @endif
        </tbody>
    </table>

</div>
@endsection
