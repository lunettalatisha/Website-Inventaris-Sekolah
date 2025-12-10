@extends('templates.app')

@section('content')
=    <div class="container my-5">
        <div class="d-flex justify-content-end">
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
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
\            </div>
        @endif

        <h5 class="mb-3">Data User</h5>
        <table class="table table-bordered">

            <tr class="text-center">
                <th>#</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
                <th>Aksi</th>
            </tr>

            {{-- data user dari controller --}}
            @foreach ($users as $key => $user)
                <tr class="text-center">
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>

                    <td>
                        @if ($user->role == 'admin')
                            <span class="badge bg-primary">
                                {{ $user->role }}
                            </span>
                        @else
                            <span class="badge bg-success">
                                {{ $user->role }}
                            </span>
                        @endif
                    </td>

                    <td class="d-flex justify-content-center">
                        <form action="{{ route('admin.users.restore', $user->id) }}" method="POST" class="me-2">
                            @csrf
                            @method('PATCH')
                            {{-- method PATCH buat restore data --}}
                            <button class="btn btn-success">
                                Kembalikan
                            </button>
                        </form>

                        {{-- utk menghapus permanen user --}}
                        <form action="{{ route('admin.users.delete_Permanent', $user->id) }}" method="POST">
                            @csrf
                            {{-- Token keamanan --}}
                            @method('DELETE')
                            {{-- method DELETE buat hapus permanen --}}
                            <button class="btn btn-danger">
                                Hapus Permanen
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
@endsection
