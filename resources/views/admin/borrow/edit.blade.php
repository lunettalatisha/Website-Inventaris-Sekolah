@extends('templates.app')

@section('content')
<div class="w-75 d-block mx-auto my-5 p-4">

    <form method="POST" action="{{ route('admin.borrowings.update', $borrowing->id) }}">
        <h5 class="text-center my-3">Edit Data Peminjaman Barang</h5>

        @csrf
        @method('PUT')

        {{-- Peminjam --}}
        <div class="mb-3">
            <label class="form-label">Nama Peminjam</label>
            <select class="form-control" name="user_id" required>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}"
                        {{ $borrowing->user_id == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Barang --}}
        <div class="mb-3">
            <label class="form-label">Barang</label>
            <select class="form-control" name="item_id" required>
                @foreach ($items as $item)
                    <option value="{{ $item->id }}"
                        {{ $borrowing->item_id == $item->id ? 'selected' : '' }}>
                        {{ $item->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Jumlah --}}
        <div class="mb-3">
            <label class="form-label">Jumlah Borrowed</label>
            <input type="number"
                   class="form-control"
                   name="quantity"
                   value="{{ $borrowing->quantity }}"
                   min="1"
                   required>
        </div>

        {{-- Tanggal Pinjam --}}
        <div class="mb-3">
            <label class="form-label">Tanggal Pinjam</label>
            <input type="date"
                   class="form-control"
                   name="borrow_date"
                   value="{{ $borrowing->borrow_date }}"
                   required>
        </div>

        {{-- Tanggal Pengembalian --}}
        <div class="mb-3">
            <label class="form-label">Tanggal Pengembalian</label>
            <input type="date"
                   class="form-control"
                   name="return_date"
                   value="{{ $borrowing->return_date }}">
        </div>

        {{-- Status --}}
        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-control" required>
                <option value="borrowed" {{ $borrowing->status == 'borrowed' ? 'selected' : '' }}>Borrowed</option>
                <option value="returned" {{ $borrowing->status == 'returned' ? 'selected' : '' }}>Returned</option>
                <option value="fine" {{ $borrowing->status == 'fine' ? 'selected' : '' }}>Fine</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">
            Update Data
        </button>

    </form>
</div>
@endsection
