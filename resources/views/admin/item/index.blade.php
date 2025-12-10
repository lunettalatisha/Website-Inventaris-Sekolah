@extends('templates.app')

@section('content')
    <div class="container mt-3">

        {{-- Alert --}}
        @if (Session::get('success'))
            <div class="alert alert-success">{{ Session::get('success') }}</div>
        @endif

        @if (Session::get('error'))
            <div class="alert alert-danger">{{ Session::get('error') }}</div>
        @endif

        {{-- aksi --}}
        <div class="d-flex justify-content-end mb-3">
            <a href="{{ route('admin.items.export') }}" class="btn btn-secondary me-2">Export (.csv)</a>
            <a href="{{ route('admin.items.trash') }}" class="btn btn-warning me-2">Data Sampah</a>
            <a href="{{ route('admin.items.create') }}" class="btn btn-success">Tambah Barang</a>
        </div>

        <h5>Data Barang</h5>

        {{-- Search --}}
        <div class="mb-3">
            <form method="GET" action="{{ route('admin.items.index') }}" class="d-flex">
                <input type="text" name="search" class="form-control me-2" placeholder="Cari nama atau kategori..."
                    value="{{ request('search') }}">
                <button class="btn btn-primary">Cari</button>
                @if (request('search'))
                    <a href="{{ route('admin.items.index') }}" class="btn btn-secondary ms-2">Reset</a>
                @endif
            </form>
        </div>

        {{-- Table --}}
        <table class="table table-bordered">
            <tr>
                <th>#</th>
                <th>Nama Barang</th>
                <th>Keterangan</th>
                <th>Kategori</th>
                <th>Jumlah</th>
                <th>Foto</th>
                <th>Aksi</th>
            </tr>

            @foreach ($items as $key => $item)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->description }}</td>
                    <td>{{ $item->category->category_name ?? 'N/A' }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>
                        @if ($item->photo)
                            <img src="{{ asset('storage/' . $item->photo) }}" width="100">
                        @else
                            Tidak ada foto
                        @endif
                    </td>
                    <td class="d-flex">
                        <a href="{{ route('admin.items.edit', $item->id) }}" class="btn btn-secondary btn-sm">
                            Edit
                        </a>
                        <form action="{{ route('admin.items.delete', $item->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm ms-2">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>

    </div>
@endsection
