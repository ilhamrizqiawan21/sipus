@extends('layouts.app')

@section('content')
  <div class="d-flex flex-column gap-3">
    <section class="d-flex flex-wrap align-items-center justify-content-between gap-3">
      <div>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-1">
            <li class="breadcrumb-item">SIPUS</li>
            <li class="breadcrumb-item">Sirkulasi</li>
            <li class="breadcrumb-item active">Anggota</li>
          </ol>
        </nav>
        <h1 class="h3 mb-1">Anggota Perpustakaan</h1>
        <p class="text-body-secondary mb-0">{{ number_format($members->total(), 0, ',', '.') }} anggota terdaftar</p>
      </div>
      <div class="d-flex flex-wrap gap-2">
        <a class="btn btn-primary" href="{{ route('members.create') }}">Tambah Anggota</a>
      </div>
    </section>

    @if(session('success'))
      <div class="alert alert-success mb-0">{{ session('success') }}</div>
    @endif

    <section class="row g-3">
      <div class="col-md-4">
        <div class="card border-primary-subtle shadow-sm h-100">
          <div class="card-body">
            <div class="fs-3 fw-bold text-primary">{{ number_format($members->total(), 0, ',', '.') }}</div>
            <div class="text-body-secondary">Total Anggota</div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card border-success-subtle shadow-sm h-100">
          <div class="card-body">
            <div class="fs-3 fw-bold text-success">{{ $members->where('is_active', true)->count() }}</div>
            <div class="text-body-secondary">Aktif di halaman ini</div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card border-warning-subtle shadow-sm h-100">
          <div class="card-body">
            <div class="fs-3 fw-bold text-warning">{{ $members->count() }}</div>
            <div class="text-body-secondary">Ditampilkan</div>
          </div>
        </div>
      </div>
    </section>

    <section class="card shadow-sm">
      <div class="card-header bg-white">
        <div class="input-group">
          <span class="input-group-text">Cari</span>
          <input class="form-control" type="search" placeholder="Nama, NIS, kode anggota">
        </div>
      </div>

      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th>Kode</th>
              <th>NIS/NIP</th>
              <th>Nama</th>
              <th>Kontak</th>
              <th>Status</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            @forelse($members as $member)
              <tr>
                <td class="mono">{{ $member->member_code }}</td>
                <td class="mono">{{ $member->nis_nisn ?? $member->nip ?? '-' }}</td>
                <td>
                  <strong>{{ $member->name }}</strong>
                  <small>{{ $member->member_type_id ? 'Tipe #' . $member->member_type_id : 'Anggota' }}</small>
                </td>
                <td>{{ $member->phone ?? $member->email ?? '-' }}</td>
                <td><span class="badge text-bg-{{ ($member->is_active ?? true) ? 'success' : 'secondary' }}">{{ ($member->is_active ?? true) ? 'Aktif' : 'Nonaktif' }}</span></td>
                <td class="text-end">
                  <div class="btn-group btn-group-sm">
                    <a class="btn btn-outline-primary" href="{{ route('members.show', $member->id) }}">Detail</a>
                    <a class="btn btn-outline-secondary" href="{{ route('members.edit', $member->id) }}">Edit</a>
                    <form method="post" action="{{ route('members.destroy', $member->id) }}">
                      @csrf @method('DELETE')
                      <button class="btn btn-outline-danger rounded-start-0" type="submit" onclick="return confirm('Hapus anggota ini?')">Hapus</button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center text-body-secondary py-5">Belum ada anggota yang tercatat.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <div class="card-footer d-flex flex-wrap align-items-center justify-content-between gap-2">
        <span>Menampilkan {{ $members->count() }} dari {{ $members->total() }} data</span>
        <div>{{ $members->links() }}</div>
      </div>
    </section>
  </div>
@endsection
