@extends('templates.app')

@section('content')
    <div class="w-75 d-block mx-auto my-5 p-4">

        <form method="POST" action="{{ route('admin.items.update', $item['id']) }}">
            <h5 class="text-center my-3">Edit Data Barang</h5>

            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="name" class="form-label">Nama Barang</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $item['name'] }}">
                @error('name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Deskripsi</label>
                <textarea id="description" class="form-control" rows="5" name="description">{{ $item['description'] }}</textarea>
                @error('description')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
@endsection
