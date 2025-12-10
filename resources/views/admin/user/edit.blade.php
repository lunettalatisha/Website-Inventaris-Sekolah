@extends('templates.app')
{{-- Menggunakan layout utama --}}

@section('content')
<div class="w-75 d-block mx-auto my-5 p-4 align-items-center">
    {{-- Container utama halaman --}}

    {{-- Breadcrumb navigasi --}}
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Pengguna</a></li>
                    <li class="breadcrumb-item"><a href="#">Data</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </nav>
        </div>
    </nav>

    {{-- Card form edit data --}}
    <div class="card p-4 m-4">
        <h5 class="text-center my-3">Edit Data Staff</h5>

        <form method="POST" action="{{ route('admin.users.update', $user['id']) }}">
            @csrf
            @method('PUT')
            {{-- Method PUT untuk update data --}}

            {{-- Input nama --}}
            <div class="mb-3">
                <label for="name" class="form-label">Nama Lengkap :</label>
                <input type="text"
                       class="form-control @error('name') is-invalid @enderror"
                       id="name"
                       name="name"
                       value="{{ $user['name'] }}">
                @error('name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- Input email --}}
            <div class="mb-3">
                <label for="email" class="form-label">Email :</label>
                <input type="email"
                       class="form-control @error('email') is-invalid @enderror"
                       id="email"
                       name="email"
                       value="{{ $user['email'] }}">
                @error('email')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- Input password --}}
            <div class="mb-3">
                <label for="password" class="form-label">Password :</label>
                <input type="password"
                       class="form-control @error('password') is-invalid @enderror"
                       id="password"
                       name="password">
                @error('password')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Edit</button>
        </form>
    </div>
</div>
@endsection
