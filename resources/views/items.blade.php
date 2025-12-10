@extends('templates.app')

@section('title', 'Items')
@section('content')
    <div class="container py-5">
        <h1 class="text-center mb-4">Barang Tersedia</h1>

        {{-- notif berhasil/error--}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        {{-- notif peringatan --}}
        @if (session('warning'))
            <div class="alert alert-warning">
                {{ session('warning') }}
            </div>
        @endif

        <div class="row">
            @foreach ($items as $item)
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm p-4 border-0 h-100 rounded-4">
                        {{-- nampilin foto --}}
                        @if ($item->photo)
                            <img src="{{ asset('storage/' . $item->photo) }}"
                                 class="card-img-top"
                                 alt="{{ $item->name }}"
                                 style="height: 200px; object-fit: cover;">
                        @endif

                        <div class="card-body">
                            <h5 class="card-title">{{ $item->name }}</h5>
                            <p class="card-text">{{ Str::limit($item->description, 100) }}</p>
                            <p class="text-muted">Stok: {{ $item->quantity }}</p>



                            {{--cekh user udh login/ blm --}}
                            @if (auth()->check())

                                {{-- Kondisi stok tersedia --}}
                                @if ($item->quantity > 0)
                                    {{-- Form peminjaman barang --}}
                                    <form action="{{ route('borrow.store', $item->id) }}" method="POST">
                                        @csrf
                                        {{--input jumlah--}}
                                        <input type="number" name="quantity" min="1"max="{{ $item->quantity }}"
                                            class="form-control mb-2"required placeholder="Jumlah">
                                        <button type="submit" class="btn btn-primary btn-sm">Pinjam</button>
                                    </form>
                                @else
                                    {{-- Pesan klo stok barang habis --}}
                                    <p class="text-danger mt-3">
                                        Stok habis
                                    </p>
                                @endif

                            {{-- klo user belum login --}}
                            @else
                                <div class="alert alert-info mt-3">
                                    <i class="bi bi-info-circle"></i>
                                    Silakan
                                    <a href="{{ route('login') }}" class="alert-link">login</a>
                                    terlebih dahulu untuk mengajukan peminjaman.
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>
@endsection
