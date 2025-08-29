<!DOCTYPE html>
<html>

<head>
  <title>Admin Login</title>
  <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <style>
    /* Ajuste del ícono dentro del input */
    .input-group .password-toggle {
      cursor: pointer;
    }
  </style>
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
              <!-- Email -->
              <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required autofocus>
              </div>

              <!-- Password con ojo -->
              <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                  <input type="password" class="form-control" id="password" name="password" required>
                  <span class="input-group-text password-toggle" onclick="togglePassword()">
                    <i id="toggle-icon" class="fa fa-eye"></i>
                  </span>
                </div>
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
      if (alert) alert.remove();
    }, 3000);

    // Mostrar / ocultar contraseña
    function togglePassword() {
      const password = document.getElementById("password");
      const icon = document.getElementById("toggle-icon");
      if (password.type === "password") {
        password.type = "text";
        icon.classList.replace("fa-eye", "fa-eye-slash");
      } else {
        password.type = "password";
        icon.classList.replace("fa-eye-slash", "fa-eye");
      }
    }
  </script>

</body>

</html>
