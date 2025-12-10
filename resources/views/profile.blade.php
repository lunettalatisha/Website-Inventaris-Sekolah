@extends('templates.app')

@section('content')
    <div class="container mt-3">
        {{-- nampilin pesan sukses/error saat nambahin data,dll --}}
        @if (Session::get('success'))
            <div class="alert alert-success">
                {{ Session::get('success') }}
            </div>
        @endif

        @if (Session::get('error'))
            <div class="alert alert-danger">
                {{ Session::get('error') }}
            </div>
        @endif

        {{-- profile --}}
        <h5 class="mt-3">My Profile</h5>
        <p><strong>Name:</strong> {{ auth()->user()->name }}</p>
        <p><strong>Email:</strong> {{ auth()->user()->email }}</p>


        {{-- riwayat --}}
        <h5 class="mt-4">Borrowing History</h5>

        {{-- search riwayat --}}
        <div class="mb-3">
            <form method="GET" action="{{ route('profile') }}" class="d-flex">
                <input type="text" name="search" class="form-control me-2" placeholder="Cari barang..."
                    value="{{ request('search') }}">

                <button type="submit" class="btn btn-primary">Cari</button>

                @if (request('search'))
                    <a href="{{ route('profile') }}" class="btn btn-secondary ms-2">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Item</th>
                    <th>Quantity</th>
                    <th>Borrow Date</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
                {{-- riwayat peminjaman --}}
                @foreach ($borrowings as $key => $borrowing)
                    <tr>
                        <td>{{ $key + 1 }}</td>

                        {{-- Nama barang yang dipinjam --}}
                        <td>{{ $borrowing->item->name ?? 'N/A' }}</td>

                        {{-- Jumlah barang yang dipinjam --}}
                        <td>{{ $borrowing->quantity }}</td>

                        {{-- Tanggal peminjaman --}}
                        <td>{{ $borrowing->borrow_date }}</td>

                        {{-- Tanggal pengembalian (jika ada) --}}
                        <td>{{ $borrowing->return_date ?? 'Not returned' }}</td>

                        {{-- Status peminjaman --}}
                        <td>
                            {{-- klo status masih dipinjam --}}
                            @if ($borrowing->status == 'borrowed')
                                <span class="badge bg-warning">
                                    Borrowed
                                </span>

                                {{-- Peringatan klo peminjaman lebih dari 3 hari --}}
                                @if (\Carbon\Carbon::parse($borrowing->borrow_date)->addDays(3)->isPast())
                                    <br>
                                    <small class="text-danger">
                                        ⚠️ Sudah lebih dari 3 hari, segera kembalikan dan bayar dendamu!
                                    </small>
                                @endif

                                {{-- klo kena denda --}}
                            @elseif ($borrowing->status == 'fine')
                                <span class="badge bg-danger">
                                    Fine
                                </span>

                                {{-- klo barang udh dikembaliin --}}
                            @else
                                <span class="badge bg-success">
                                    Returned
                                </span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
