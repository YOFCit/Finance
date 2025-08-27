@extends('welcome')
@section('title', '404 Not Found')
@section('content')
<div class="text-center p-5">
  <h1 class="display-4 fw-bold text-danger">404</h1>
  <p class="lead">The page you are looking for was not found.</p>
  <a href="{{ route('home') }}" class="btn btn-primary mt-3">
    Go back to Home
  </a>
</div>
@endsection