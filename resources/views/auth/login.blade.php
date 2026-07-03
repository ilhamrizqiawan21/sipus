@extends('layouts.app')

@section('content')
  <div class="auth-wrap">
    <form class="auth-card" method="POST" action="/login">
      @csrf
      <div class="auth-header">
        <div class="brand-mark">SA</div>
        <h1>Masuk SIPUS</h1>
        <p>Gunakan akun petugas perpustakaan.</p>
      </div>
      <label>Email
        <input type="email" name="email" required>
      </label>
      <label>Password
        <input type="password" name="password" required>
      </label>
      <button class="btn btn-primary full" type="submit">Login</button>
    </form>
  </div>
@endsection
