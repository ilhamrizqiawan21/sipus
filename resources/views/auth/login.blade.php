@extends('layouts.auth')

@section('content')
  <div class="auth-wrap">
    <form class="auth-card" method="POST" action="{{ route('login') }}">
      @csrf
      <div class="auth-header">
        <img class="auth-logo" src="{{ asset('logo-sekolah.png') }}" alt="Logo MTs Al-Ihsan">
        <h1>Masuk SIPUS</h1>
        <p>Sistem Informasi Perpustakaan MTs Al-Ihsan.</p>
      </div>
      @if(session('success'))
        <div class="alert alert-success mb-3">{{ session('success') }}</div>
      @endif
      @if($errors->any())
        <div class="alert alert-danger mb-3">{{ $errors->first() }}</div>
      @endif
      <label>Email
        <input type="email" name="email" value="{{ old('email') }}" required autofocus>
      </label>
      <label>Password
        <input type="password" name="password" required>
      </label>
      <label class="d-flex align-items-center gap-2">
        <input type="checkbox" name="remember" value="1">
        <span>Ingat saya</span>
      </label>
      <button class="btn btn-primary full" type="submit">Login</button>
    </form>
  </div>
@endsection
