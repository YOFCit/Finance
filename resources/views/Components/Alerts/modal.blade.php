<!-- ALERT TOAST -->
<!-- Tu CSS externo -->
<link href="{{ asset('css/toast.css') }}" rel="stylesheet">

<div aria-live="polite" aria-atomic="true" class="toast-container" style="z-index: 1080;">

  <!-- SUCCESS ALERT -->
  @if(session('success'))
  <div class="toast align-items-center text-bg-success border-0 mb-2" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body">
        {{ session('success') }}
      </div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
  </div>
  @endif

  <!-- ERROR ALERT -->
  @if($errors->any())
  <div class="toast align-items-center text-bg-danger border-0 mb-2" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body">
        <ul class="mb-0">
          @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    </div>
  </div>
  @endif
</div>
<script src="{{ asset('js/toast.js') }}"></script>

<style>

</style>