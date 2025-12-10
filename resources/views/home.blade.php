@extends('templates.app')

@section('title', 'Home')
@section('content')
<div class="container py-5 mt-4">
    <div class="row align-items-center">
        {{-- Hero Section (teks) --}}
        <div class="col-lg-6 col-md-6 text-center text-md-start mb-5 mb-md-0">
            <h1 class="fw-bold mb-3 display-4">
                Selamat Datang di <span class="text-primary">P!NJAM</span>
            </h1>
            <p class="lead text-muted mb-4">
                Pinjam barang, tanpa ribet, tanpa antre. Sistem kami memastikan proses peminjaman
                berjalan cepat dan efisien.
            </p>
        </div>

        {{-- Hero Section (gambar) --}}
        <div class="col-lg-6 col-md-6 text-center">
            <img src="https://img.icons8.com/color/350/000000/school-backpack.png"
                 alt="Backpack"
                 class="img-fluid"
                 style="max-width: 320px;">
        </div>
    </div>
</div>

{{-- Kategori Barang --}}
<div class="container py-5">
    <h2 class="fw-bold mb-4 display-5 text-center">Kategori Barang</h2>

    <div class="row g-4 justify-content-center">
        @foreach ($categories as $category)
            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                <a href="{{ route('items.by_category', $category->id) }}" class="text-decoration-none">
                    <div class="card category-card shadow-sm border-0 rounded-4 h-100">
                        <div class="card-body d-flex flex-column align-items-center justify-content-center p-4">
                            <div class="mb-3">
                                <i class="bi bi-clipboard-check text-primary fs-1"></i>
                            </div>
                            <h5 class="fw-bold text-dark mb-2 text-center">
                                {{ $category->category_name }}
                            </h5>
                            <p class="text-muted small mb-0 text-center">
                                {{ $category->items_count }} barang
                            </p>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</div>

{{-- Styling --}}
<style>
    .category-card {
        transition: all 0.3s ease;
        min-height: 180px;
        box-shadow: 0 3px 10px rgba(80, 81, 84, 0.1);
        border-radius: 12px;
        position: relative;
        overflow: hidden;
    }

    .category-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(13, 110, 253, 0.18);
    }

    .category-card::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: #0d6efd;
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }

    .category-card:hover::after {
        transform: scaleX(1);
    }

    @media (max-width: 768px) {
        .display-4 { font-size: 2.2rem; }
        .display-5 { font-size: 1.8rem; }
        .category-card { min-height: 160px; }
    }
</style>
@endsection
