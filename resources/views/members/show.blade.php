@extends('layouts.app')

@section('content')
  <div class="d-flex flex-column gap-3">
    <section class="d-flex flex-wrap align-items-center justify-content-between gap-3">
      <div>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-1">
            <li class="breadcrumb-item"><a href="{{ route('members.index') }}">Anggota</a></li>
            <li class="breadcrumb-item active">Detail</li>
          </ol>
        </nav>
        <h1 class="h3 mb-1">{{ $member->name }}</h1>
        <p class="text-body-secondary mb-0">{{ $member->member_code }}</p>
      </div>
      <div class="d-flex flex-wrap gap-2">
        <a class="btn btn-outline-secondary" href="{{ route('members.index') }}">Kembali</a>
        <a class="btn btn-primary" href="{{ route('members.edit', $member->id) }}">Edit</a>
      </div>
    </section>

    @if(session('success'))
      <div class="alert alert-success mb-0">{{ session('success') }}</div>
    @endif

    <section class="card shadow-sm">
      <div class="card-header bg-white d-flex align-items-center justify-content-between">
        <h2 class="h5 mb-0">Informasi Anggota</h2>
        <span class="badge text-bg-{{ ($member->is_active ?? true) ? 'success' : 'secondary' }}">{{ ($member->is_active ?? true) ? 'Aktif' : 'Nonaktif' }}</span>
      </div>
      <div class="card-body">
        <div class="row g-3">
          <div class="col-md-4"><div class="text-body-secondary small">Kode Anggota</div><div class="fw-semibold">{{ $member->member_code }}</div></div>
          <div class="col-md-4"><div class="text-body-secondary small">NIS/NIP</div><div class="fw-semibold">{{ $member->nis_nisn ?? $member->nip ?? '-' }}</div></div>
          <div class="col-md-4"><div class="text-body-secondary small">Jenis</div><div class="fw-semibold">{{ $member->memberType?->name ?? 'Anggota' }}</div></div>
          <div class="col-md-4"><div class="text-body-secondary small">Kelas</div><div class="fw-semibold">{{ $member->class?->name ?? '-' }}</div></div>
          <div class="col-md-4"><div class="text-body-secondary small">Telepon</div><div class="fw-semibold">{{ $member->phone ?? '-' }}</div></div>
          <div class="col-md-4"><div class="text-body-secondary small">Email</div><div class="fw-semibold">{{ $member->email ?? '-' }}</div></div>
          <div class="col-md-4"><div class="text-body-secondary small">Tanggal Bergabung</div><div class="fw-semibold">{{ $member->join_date ? \Illuminate\Support\Carbon::parse($member->join_date)->format('d M Y') : '-' }}</div></div>
          <div class="col-12"><div class="text-body-secondary small">Alamat</div><div class="fw-semibold">{{ $member->address ?? '-' }}</div></div>
        </div>
      </div>
    </section>
  </div>
@endsection
