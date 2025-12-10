@extends('templates.app')

@section('content')
<div class="w-75 d-block mx-auto my-5 p-4">

    <form method="POST" action="{{ route('admin.borrowings.store') }}">
        @csrf

        <h5 class="text-center my-3">Tambah Data Peminjaman Barang</h5>

        {{-- Peminjam --}}
        <div class="mb-3">
            <label for="user_id" class="form-label">Nama Peminjam</label>
            <select class="form-control" id="user_id" name="user_id" required>
                <option value="">Pilih Peminjam</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Barang --}}
        <div class="mb-3">
            <label for="item_id" class="form-label">Barang</label>
            <select class="form-control" id="item_id" name="item_id" required>
                <option value="">Pilih Barang</option>
                @foreach ($items as $item)
                    <option value="{{ $item->id }}">
                        {{ $item->name }} (stok: {{ $item->quantity }})
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Jumlah --}}
        <div class="mb-3">
            <label for="quantity" class="form-label">Jumlah Borrowed</label>
            <input type="number"
                   class="form-control"
                   id="quantity"
                   name="quantity"
                   min="1"
                   required>
        </div>

        <button type="submit" class="btn btn-primary">
            Simpan Peminjaman
        </button>

    </form>
</div>
@endsection
