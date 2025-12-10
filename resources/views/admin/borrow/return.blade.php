@extends('templates.app')

@section('content')
    <div class="container my-5">
        <h5 class="mb-3">Kembalikan Barang</h5>
        <form method="POST" action="{{ route('admin.borrowings.return', $borrowing->id) }}">
            @csrf
            @method('PATCH')
            <button type="submit" class="btn btn-primary">Kembalikan Barang</button>
        </form>
    </div>
@endsection
