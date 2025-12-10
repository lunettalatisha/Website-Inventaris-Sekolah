<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - P!NJAM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light d-flex justify-content-center align-items-center vh-100">
    <div class="card shadow-lg p-4 rounded-3" style="width: 380px;">
        <h4 class="text-center mb-4 fw-bold">Register</h4>

        {{-- Form register --}}
        <form method="POST" action="{{ route('signup.register') }}">
            @csrf
            <!-- Name Input -->
            <div class="mb-3">
                <input type="text"
                       class="form-control shadow-sm @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}"
                       placeholder="Enter your name" required>

                @error('name')
                    <div class="invalid-feedback d-block mt-1">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Email Input -->
            <div class="mb-3">
                <input type="email"
                       class="form-control shadow-sm @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}"
                       placeholder="Enter your email" required>

                @error('email')
                    <div class="invalid-feedback d-block mt-1">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="mb-3">
                <input type="password"
                       class="form-control shadow-sm @error('password') is-invalid @enderror" name="password"
                       placeholder="Enter password" required>

                @error('password')
                    <div class="invalid-feedback d-block mt-1">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary w-100 fw-semibold shadow-sm">
                Sign Up
            </button>

            <!-- Links -->
            <div class="text-center mt-3 pt-3">
                <small>
                    Already have an account?
                    <a href="{{ route('login') }}" class="text-decoration-none">Login here</a>
                </small><br>
                <small>
                    <a href="{{ route('home') }}" class="text-decoration-none">Back to Home</a>
                </small>
            </div>
        </form>
    </div>

    <style>
        /* ngilangin border default card */
        .card {
            border: none;
        }

        /* Styling input form */
        .form-control {
            border: 1px solid #dee2e6;
            padding: 10px 15px;
        }

        /* efek garis biru di bwh kategori */
        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }

        /* register */
        .btn-primary {
            padding: 10px;
        }

        /* Warna link */
        a {
            color: #0d6efd;
        }
        a:hover {
            color: #0a58ca;
        }

        .invalid-feedback {
            font-size: 0.875em;
        }
    </style>
</body>
</html>
