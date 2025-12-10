<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'School Inventory')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    {{-- Stack untuk style tambahan dari child views --}}
    @stack('styles')

    <style>
        .navbar-custom {
            background: #6a11cb;
            padding: 0.8rem 1rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        /* logo pinjam */
        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 800;
            font-size: 1.4rem;
            color: #fff;
            text-decoration: none;
        }

        /* icon */
        .navbar-brand i {
            font-size: 1.5rem;
        }

        /* menu navbar */
        .navbar-nav .nav-link {
            color: #fff !important;
            font-weight: 500;
            margin: 0 8px;
            transition: 0.3s;
            padding: 8px 12px;
        }

        .navbar-nav .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
        }

        /* login */
        .btn-login {
            background: #6a11cb;
            color: #fff;
            font-weight: 600;
            border-radius: 20px;
            padding: 6px 16px;
        }

        .btn-login:hover {
            background: #fff;
            color: #6a11cb;
        }

        /* signup dan logout */
        .btn-signup {
            color: #6a11cb;
            background-color: #fff;
            border: 2px solid #6a11cb;
            font-weight: 600;
            border-radius: 20px;
            padding: 6px 16px;
        }

        .btn-signup:hover {
            background: #6a11cb;
            color: #fff;
        }

        /* Custom dropdown button for Data Master */
        .btn-data-master {
            color: #fff !important;
            background-color: transparent;
            font-weight: 500;
            border-radius: 20px;
            padding: 6px 16px;
            margin: 0 8px;
        }



        /* Remove the default dropdown arrow hover effect */
        .btn-data-master.dropdown-toggle::after {
            border-top-color: #fff;
        }

        /* Fix for navbar toggler icon color */
        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 1%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        .navbar-toggler {
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="bi bi-backpack-fill"></i> P!NJAM
            </a>
            <!-- togglr utk tampilan mobile -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Home</a>
                    </li>

                    <!-- klo user login sebagai admin -->
                    @if (auth()->check() && auth()->user()->role === 'admin')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>

                        <!-- Data Master dropdown (admin) -->
                        <li class="nav-item dropdown">
                            <button class="btn btn-data-master dropdown-toggle" type="button"
                                id="masterDataDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-list-check me-2"></i>Data Master
                            </button>

                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="masterDataDropdown">
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.users.index') }}">
                                        <i class="bi bi-people me-2"></i>Data Pengguna
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.items.index') }}">
                                        <i class="bi bi-box-seam me-2"></i>Data Barang
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.borrowings.index') }}">
                                        <i class="bi bi-arrow-left-right me-2"></i>Data Peminjaman & Pengembalian
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST" class="d-inline m-0 p-0">
                                @csrf
                                <button type="submit" class="btn btn-signup ms-2">Logout</button>
                            </form>
                        </li>

                        <!-- klo user login sbg user biasa -->
                    @elseif(auth()->check() && auth()->user()->role === 'user')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('profile') }}">My Profile</a>
                        </li>

                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST" class="d-inline m-0 p-0">
                                @csrf
                                <button type="submit" class="btn btn-signup ms-2">Logout</button>
                            </form>
                        </li>

                        <!-- klo blm login -->
                    @else
                        <li class="nav-item">
                            <a class="btn btn-login" href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-signup ms-2" href="{{ route('signup') }}">Sign Up</a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    {{-- Main content area --}}
    <main class="py-4">
        <div class="container">
            @yield('content')
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    {{-- jQuery (diperlukan untuk chart di dashboard) --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    {{-- Chart JS --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- Stack untuk script tambahan dari child views --}}
    @stack('scripts')
</body>
</html>
