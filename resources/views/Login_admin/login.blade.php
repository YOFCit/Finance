<!DOCTYPE html>
<html>

<head>
  <title>Admin Login</title>
  <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
</head>

<body class="bg-light d-flex align-items-center justify-content-center min-vh-100">

  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6 col-lg-4">
        <div class="card shadow">
          <div class="card-body p-4">
            <h2 class="text-center mb-4">Admin Login</h2>

            @if($errors->any())
            <div id="alert-message" class="alert alert-danger">
              {{ $errors->first() }}
            </div>
            @endif

            <form method="POST" action="{{ route('admin.login.post') }}">
              @csrf
              <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required autofocus>
              </div>
              <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
              </div>
              <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Ocultar mensaje de error después de 3 segundos
    setTimeout(() => {
      const alert = document.getElementById("alert-message");
      if (alert) {
        alert.remove();
      }
    }, 3000);
  </script>

</body>

</html>