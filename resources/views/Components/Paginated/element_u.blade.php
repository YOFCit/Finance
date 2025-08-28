@if($usuarios->hasPages())
<div class="d-flex justify-content-center mt-3 mb-4">
  <nav>
    <ul class="pagination pagination-sm mb-0 shadow-sm rounded flex-wrap">
      {{-- Primera página --}}
      <li class="page-item {{ $usuarios->onFirstPage() ? 'disabled' : '' }}">
        <a class="page-link" href="{{ $usuarios->url(1) }}" aria-label="First">««</a>
      </li>

      {{-- Página anterior --}}
      <li class="page-item {{ $usuarios->onFirstPage() ? 'disabled' : '' }}">
        <a class="page-link" href="{{ $usuarios->previousPageUrl() }}" aria-label="Previous">«</a>
      </li>

      {{-- Páginas dinámicas --}}
      @php
      $start = max($usuarios->currentPage() - 2, 1);
      $end = min($usuarios->currentPage() + 2, $usuarios->lastPage());
      @endphp

      @for ($page = $start; $page <= $end; $page++)
        <li class="page-item {{ $page == $usuarios->currentPage() ? 'active' : '' }}">
        <a class="page-link" href="{{ $usuarios->url($page) }}">{{ $page }}</a>
        </li>
        @endfor

        {{-- Página siguiente --}}
        <li class="page-item {{ $usuarios->hasMorePages() ? '' : 'disabled' }}">
          <a class="page-link" href="{{ $usuarios->nextPageUrl() }}" aria-label="Next">»</a>
        </li>

        {{-- Última página --}}
        <li class="page-item {{ $usuarios->hasMorePages() ? '' : 'disabled' }}">
          <a class="page-link" href="{{ $usuarios->url($usuarios->lastPage()) }}" aria-label="Last">»»</a>
        </li>
    </ul>
  </nav>
</div>
@endif