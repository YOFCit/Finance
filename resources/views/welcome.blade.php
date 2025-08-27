<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <title>@yield('title', 'My App')</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <!-- Bootstrap 5 -->
  <link href="{{ asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet" />
  <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
  <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">

</head>

<body class="d-flex flex-column min-vh-100">
  <!-- MESSAGE ALERTS -->
  @include('Components.Alerts.modal')

  <header class="bg-dark text-white py-3">
    <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">

      <!-- Título y subtítulo -->
      <div class="text-center text-md-start">
        <h1 class="h5 mb-0 d-flex align-items-center justify-content-center justify-content-md-start gap-2">
          <i class="bi {{ Route::is('users.index') ? 'bi-person' : 'bi-credit-card' }}"></i>
          @yield('title', 'My App')
        </h1>
        <small class="opacity-75 d-block">Urgent Payment Request System</small>
      </div>

      @if(Auth::check())
      <div class="d-flex flex-row align-items-center gap-2 mt-2 mt-md-0">
        <!-- User Dropdown -->
        <div class="dropdown">
          <button class="btn btn-outline-light btn-sm dropdown-toggle d-flex align-items-center gap-1"
            type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-person-circle"></i>
            <span class="d-none d-md-inline">{{ $auth_user->name ?? Auth::user()->name }}</span>
          </button>

          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
            <li>
              <a href="{{ route('users.index') }}" class="dropdown-item">
                <i class="bi bi-eye me-2"></i> Employees
              </a>
            </li>

            <li>
              @php
              $approvalPerson = \App\Models\ApprovalPerson::first();
              @endphp
              <!-- Dropdown item that triggers the modal -->
              <a href="javascript:void(0);"
                class="dropdown-item"
                data-bs-toggle="modal"
                data-bs-target="#approvalModal">
                <i class="bi bi-person-check me-2"></i>
                {{ $approvalPerson ? 'Edit Approver' : 'Create Approver' }}
              </a>
            </li>

            <li>
              <form action="{{ route('admin.logout') }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="dropdown-item text-danger">
                  <i class="bi bi-box-arrow-right me-2"></i> Log Out
                </button>
              </form>
            </li>
          </ul>
        </div>

        <!-- Return Button -->
        @if (Route::is('users.index'))
        <a href="{{ route('home') }}" class="btn btn-outline-light btn-sm d-flex align-items-center gap-1">
          <i class="bi bi-arrow-left-circle"></i>
          <span class="d-none d-md-inline">Return</span>
        </a>
        @endif
      </div>
      @endif
    </div>
  </header>


  <!-- MAIN CONTENT -->
  <main class="flex-grow-1">
    @yield('content')
    @if(Auth::check())
    @include('Employees.modals.approval')
    @endif
  </main>

  <!-- FOOTER -->
  <footer class="footer">
    <div class="container text-center">
      <small>&copy; 2025 The Finance Department. All rights reserved.</small>
    </div>
  </footer>

  <script src="{{ asset('bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <!-- jQuery -->
  <script src="{{ asset('js/jquery.min.js') }}"></script>
  <!-- Signature Pad -->
  <script src="{{ asset('js/signature_pad.umd.min.js') }}"></script>
  <!-- Scripts -->
  <script src="{{ asset('js/payment-requests.js') }}"></script>
</body>

</html>