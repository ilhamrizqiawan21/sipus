@extends('layouts.auth')

@section('content')
  <div class="auth-wrap">
    <form class="auth-card" method="POST" action="/register">
      @csrf
      <div class="auth-header">
        <img class="auth-logo" src="{{ asset('logo-sekolah.png') }}" alt="Logo MTs Al-Ihsan">
        <h1>Registrasi Petugas</h1>
        <p>Buat akun pengguna SIPUS MTs Al-Ihsan.</p>
      </div>
      <label>Nama
        <input type="text" name="name" required>
      </label>
      <label>Email
        <input type="email" name="email" required>
      </label>
      <label>Password
        <input type="password" name="password" required>
      </label>
      <button class="btn btn-primary full" type="submit">Register</button>
    </form>
  </div>
@endsection
