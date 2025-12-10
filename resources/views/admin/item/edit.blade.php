@extends('templates.app')

@section('content')
    <div class="w-75 d-block mx-auto my-5 p-4">

        <form method="POST" action="{{ route('admin.items.update', $item->id) }}" enctype="multipart/form-data">

            <h5 class="text-center my-3">Edit Data Barang</h5>

            @csrf
            @method('PUT')

            {{-- Nama Barang --}}
            <div class="mb-3">
                <label for="name" class="form-label">Nama Barang:</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $item->name }}">

                @error('name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- Kategori --}}
            <div class="mb-3">
                <label for="category_id" class="form-label">Kategori:</label>
                <select class="form-control" id="category_id" name="category_id" required>
                    <option value="">Pilih Kategori</option>
                    @foreach (\App\Models\Category::all() as $category)
                        <option value="{{ $category->id }}" {{ $item->category_id == $category->id ? 'selected' : '' }}>
                            {{ $category->category_name }}
                        </option>
                    @endforeach
                </select>

                @error('category_id')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- Jumlah --}}
            <div class="mb-3">
                <label for="quantity" class="form-label">Jumlah Barang:</label>
                <input type="number" class="form-control" id="quantity" name="quantity" value="{{ $item->quantity }}"
                    min="1" required>

                @error('quantity')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- Keterangan --}}
            <div class="mb-3">
                <label for="description" class="form-label">Keterangan:</label>
                <textarea id="description" rows="5" class="form-control" name="description">{{ $item->description }}</textarea>

                @error('description')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- Foto --}}
            <div class="mb-3">
                <label for="photo" class="form-label">Foto Barang:</label>
                <input type="file" class="form-control" id="photo" name="photo" accept="image/*">

                @if ($item->photo)
                    <img src="{{ asset('storage/' . $item->photo) }}" class="mt-2" style="max-width: 200px;">
                @endif

                @error('photo')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">
                Update Data
            </button>

        </form>
    </div>
@endsection
