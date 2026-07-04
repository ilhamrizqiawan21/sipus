@extends('layouts.app')

@section('content')
  <div class="d-flex flex-column gap-3">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
      <div>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-1">
            <li class="breadcrumb-item"><a href="{{ route('members.index') }}">Anggota</a></li>
            <li class="breadcrumb-item active">Tambah</li>
          </ol>
        </nav>
        <h1 class="h3 mb-1">Tambah Anggota</h1>
        <p class="text-body-secondary mb-0">Lengkapi data anggota perpustakaan.</p>
      </div>
      <a class="btn btn-outline-secondary" href="{{ route('members.index') }}">Kembali</a>
    </div>

    <div class="card shadow-sm">
      <div class="card-body">
        <form method="post" action="{{ route('members.store') }}">
          @include('members._form')
        </form>
      </div>
    </div>
  </div>
@endsection
