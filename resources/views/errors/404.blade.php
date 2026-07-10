@extends('layouts.app')

@section('content')
<div class="error-page">
  <div class="error-code">404</div>
  <h1>Halaman Tidak Ditemukan</h1>
  <p>Alamat yang dibuka tidak tersedia atau sudah dipindahkan.</p>
  <div class="d-flex justify-content-center gap-2">
    <a class="btn btn-primary" href="{{ route('home') }}">Dashboard</a>
    <button class="btn btn-outline-secondary" type="button" onclick="history.back()">Kembali</button>
  </div>
</div>
@endsection
