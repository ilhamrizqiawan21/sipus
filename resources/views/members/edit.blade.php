@extends('layouts.app')

@section('content')
  <div class="d-flex flex-column gap-3">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
      <div>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-1">
            <li class="breadcrumb-item"><a href="{{ route('members.index') }}">Anggota</a></li>
            <li class="breadcrumb-item active">Edit</li>
          </ol>
        </nav>
        <h1 class="h3 mb-1">Edit Anggota</h1>
        <p class="text-body-secondary mb-0">{{ $member->name }}</p>
      </div>
      <a class="btn btn-outline-secondary" href="{{ route('members.show', $member->id) }}">Detail</a>
    </div>

    <div class="card shadow-sm">
      <div class="card-body">
        <form method="post" action="{{ route('members.update', $member->id) }}">
          @method('PUT')
          @include('members._form')
        </form>
      </div>
    </div>
  </div>
@endsection
