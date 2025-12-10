@extends('templates.app')

@section('content')
    <div class="container mt-3">
        @if (Session::get('success'))
            <div class="alert alert-success">
                {{ Session::get('success') }}
            </div>
        @endif

        {{-- aksi --}}
        <div class="d-flex justify-content-end">
            <a href="{{ route('admin.users.export') }}" class="btn btn-secondary me-2">Export (.csv)</a>
            <a href="{{ route('admin.users.trash') }}" class="btn btn-warning ms-2">Data Sampah</a>
            <a href="{{ route('admin.users.create') }}" class="btn btn-success ms-2">Tambah Pengguna</a>
        </div>

        <h5 class="mt-3">Data Pengguna (Admin/Staff & User)</h5>
        {{-- SEARCH FORM --}}
        <div class="mb-3">
            <form method="GET" action="{{ route('admin.users.index') }}" class="d-flex">

                <input type="text" name="search" class="form-control me-2"
                    placeholder="Cari berdasarkan nama atau email..." value="{{ request('search') }}">

                <button type="submit" class="btn btn-primary">Cari</button>
                @if (request('search'))
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary ms-2">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        {{-- TABLE DATA --}}
        <table class="table table-bordered">
            <tr>
                <th>#</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
                <th>Aksi</th>
            </tr>

            {{-- data pengguna --}}
            @foreach ($users as $key => $item)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $item['name'] }}</td>
                    <td>{{ $item['email'] }}</td>

                    {{-- role pengguna --}}
                    <td>
                        @if ($item['role'] == 'admin')
                            <span class="badge badge-primary text-dark">
                                {{ $item['role'] }}
                            </span>
                        @else
                            <span class="badge badge-success text-dark">
                                {{ $item['role'] }}
                            </span>
                        @endif
                    </td>

                    {{-- Tombol aksi --}}
                    <td class="d-flex gap-2">
                        <a href="{{ route('admin.users.edit', $item['id']) }}" class="btn btn-secondary btn-sm">
                            Edit
                        </a>

                        {{-- Form hapus --}}
                        <form action="{{ route('admin.users.delete', $item['id']) }}" method="POST"
                            onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                            @csrf
                            @method('DELETE')
                            {{-- Method DELETE --}}
                            <button type="submit" class="btn btn-danger btn-sm">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
@endsection
