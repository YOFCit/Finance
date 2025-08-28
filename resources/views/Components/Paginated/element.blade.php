@if($requests->hasPages())
<div class="d-flex justify-content-center mt-3 mb-4">
  <nav>
    <ul class="pagination pagination-sm mb-0 shadow-sm rounded flex-wrap">
      {{-- Primera página --}}
      <li class="page-item {{ $requests->onFirstPage() ? 'disabled' : '' }}">
        <a class="page-link" href="{{ $requests->url(1) }}" aria-label="First">««</a>
      </li>

      {{-- Botón Anterior --}}
      <li class="page-item {{ $requests->onFirstPage() ? 'disabled' : '' }}">
        <a class="page-link" href="{{ $requests->previousPageUrl() }}" aria-label="Previous">«</a>
      </li>

      {{-- Páginas dinámicas --}}
      @php
      $start = max($requests->currentPage() - 2, 1);
      $end = min($requests->currentPage() + 2, $requests->lastPage());
      @endphp

      @for ($page = $start; $page <= $end; $page++)
        <li class="page-item {{ $page == $requests->currentPage() ? 'active' : '' }}">
        <a class="page-link" href="{{ $requests->url($page) }}">{{ $page }}</a>
        </li>
        @endfor

        {{-- Botón Siguiente --}}
        <li class="page-item {{ $requests->hasMorePages() ? '' : 'disabled' }}">
          <a class="page-link" href="{{ $requests->nextPageUrl() }}" aria-label="Next">»</a>
        </li>

        {{-- Última página --}}
        <li class="page-item {{ $requests->currentPage() == $requests->lastPage() ? 'disabled' : '' }}">
          <a class="page-link" href="{{ $requests->url($requests->lastPage()) }}" aria-label="Last">»»</a>
        </li>
    </ul>
  </nav>
</div>
@endif