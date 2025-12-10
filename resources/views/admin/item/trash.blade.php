@extends('templates.app')

@section('content')
<div class="container my-5">

    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('admin.items.index') }}" class="btn btn-secondary">Kembali</a>
    </div>

    {{-- Alert message --}}
    @if (Session::get('success'))
        <div class="alert alert-success">{{ Session::get('success') }}</div>
    @endif

    @if (Session::get('error'))
        <div class="alert alert-danger">{{ Session::get('error') }}</div>
    @endif

    <h5 class="mb-3">Data Barang</h5>

    <table class="table table-bordered">
        <tr class="text-center">
            <th>#</th>
            <th>Nama Barang</th>
            <th>Kategori</th>
            <th>Deskripsi</th>
            <th>Jumlah</th>
            <th>Foto</th>
            <th>Aksi</th>
        </tr>

        @foreach ($items as $key => $item)
        {{-- Data barang terhapus --}}
            <tr class="text-center">
                <td>{{ $key + 1 }}</td>
                <td>{{ $item->name }}</td>
                <td>{{ $item->category->category_name ?? 'N/A' }}</td>
                <td>{{ $item->description }}</td>
                <td>{{ $item->quantity }}</td>
                <td>
                    @if ($item->photo)
                        <img src="{{ asset('storage/' . $item->photo) }}"
                             alt="Foto Barang" style="max-width: 100px;">
                    @else
                        Tidak ada foto
                    @endif
                </td>
                <td class="d-flex justify-content-center">
                    {{-- Restore --}}
                    <form action="{{ route('admin.items.restore', $item->id) }}" method="POST" class="me-2">
                        @csrf
                        @method('PATCH')
                        <button class="btn btn-success">Kembalikan</button>
                    </form>

                    {{-- Hapus permanen --}}
                    <form action="{{ route('admin.items.delete_Permanent', $item->id) }}" method="POST">
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
