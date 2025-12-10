<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login - P!NJAM</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light d-flex justify-content-center align-items-center vh-100">

  <div class="card shadow-lg p-4 rounded-3" style="width: 380px;">
    <h4 class="text-center mb-4 fw-bold">Login</h4>

    @if (session('success'))
      <div class="alert alert-success text-center py-2 mb-3">
        {{ session('success') }}
      </div>
    @endif

    @if (session('error'))
      <div class="alert alert-danger text-center py-2 mb-3">
        {{ session('error') }}
      </div>
    @endif

    {{-- login --}}
    <form action="{{ route('login') }}" method="POST">
      @csrf
      <div class="mb-3">
          <input type="text" name="email" id="login" class="form-control shadow-sm"
          placeholder="Enter your email or username"required>
      </div>

      <div class="mb-3">
          <input type="password" name="password" id="password" class="form-control shadow-sm"
              placeholder="Enter your password"required>
     </div>

      <button type="submit" class="btn btn-primary w-100 fw-semibold shadow-sm">
        Login
      </button>

      <div class="text-center mt-3 pt-3">
          <small>
            Don't have any account?
            <a href="{{ route('signup') }}" class="text-decoration-none">
              Regist here!
            </a>
          </small>
      </div>
    </form>
</div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <style>
    .card {
      border: none;
    }

    .form-control {
      border: 1px solid #dee2e6;
      padding: 10px 15px;
    }

    .form-control:focus {
      border-color: #0d6efd;
      box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }

    /* login */
    .btn-primary {
      padding: 10px;
    }

    .alert {
      border: none;
      border-radius: 8px;
    }

    /* Warna link */
    a {
      color: #0d6efd;
    }
    a:hover {
      color: #0a58ca;
    }
  </style>
</body>
</html>
