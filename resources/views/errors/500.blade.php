@extends('layouts.app')

@section('content')
<div class="error-page">
  <div class="error-code">500</div>
  <h1>Terjadi Kesalahan</h1>
  <p>Sistem tidak dapat memproses permintaan saat ini. Silakan coba lagi atau hubungi pengelola aplikasi.</p>
  <div class="d-flex justify-content-center gap-2">
    <a class="btn btn-primary" href="{{ route('home') }}">Dashboard</a>
    <button class="btn btn-outline-secondary" type="button" onclick="history.back()">Kembali</button>
  </div>
</div>
@endsection
