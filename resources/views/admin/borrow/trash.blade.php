@extends('templates.app')

@section('content')
    <div class="container my-5">

        <div class="d-flex justify-content-end">
            <a href="{{ route('admin.borrowings.index') }}" class="btn btn-secondary">
                Kembali
            </a>
        </div>

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

        <h5 class="mb-3">Data Peminjaman Terhapus</h5>

        <table class="table table-bordered">
            <tr class="text-center">
                <th>#</th>
                <th>Nama Peminjam</th>
                <th>Barang</th>
                <th>Jumlah</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Kembali</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>

            @foreach ($borrowings as $key => $borrowing)
                <tr class="text-center">
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $borrowing->user->name }}</td>
                    <td>{{ $borrowing->item->name }}</td>
                    <td>{{ $borrowing->quantity }}</td>
                    <td>{{ $borrowing->borrow_date }}</td>
                    <td>{{ $borrowing->return_date ?? '-' }}</td>

                    <td>
                        @if ($borrowing->status === 'borrowed')
                            <span class="badge bg-warning">Borrowed</span>
                        @else
                            <span class="badge bg-success">Returned</span>
                        @endif
                    </td>

                    <td class="d-flex justify-content-center">
                        <form action="{{ route('admin.borrowings.restore', $borrowing->id) }}" method="POST"
                            class="me-2">
                            @csrf
                            @method('PATCH')
                            <button class="btn btn-success">Pulihkan</button>
                        </form>

                        <form action="{{ route('admin.borrowings.delete_Permanent', $borrowing->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger">Hapus Permanen</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
@endsection
