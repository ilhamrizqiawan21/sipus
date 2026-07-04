@extends('layouts.app')

@section('content')
  <div class="d-flex flex-column gap-3">
    <section class="d-flex flex-wrap align-items-center justify-content-between gap-3">
      <div>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-1">
            <li class="breadcrumb-item"><a href="{{ route('copies.index') }}">Eksemplar</a></li>
            <li class="breadcrumb-item active">Tambah</li>
          </ol>
        </nav>
        <h1 class="h3 mb-1">Tambah Eksemplar</h1>
      </div>
      <a class="btn btn-outline-secondary" href="{{ route('copies.index') }}">Batal</a>
    </section>

    @if($errors->any())
      <div class="alert alert-danger mb-0">
        <ul class="mb-0">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <section class="card shadow-sm">
      <div class="card-body">
        <form method="post" action="{{ route('copies.store') }}">
          @csrf
          @include('copies._form')
        </form>
      </div>
    </section>
  </div>
@endsection
