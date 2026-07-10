@extends('layouts.app')

@section('content')
<div class="d-flex flex-column gap-3">
  <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
    <div>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-1">
          <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Laporan</a></li>
          <li class="breadcrumb-item active">Anggota Aktif</li>
        </ol>
      </nav>
      <h1 class="h3 mb-1">Laporan Anggota Aktif</h1>
      <p class="text-body-secondary mb-0">Daftar anggota aktif berdasarkan tanggal bergabung atau tanggal dibuat.</p>
    </div>
    <a href="{{ route('reports.index', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="btn btn-outline-secondary">Kembali</a>
  </div>

  <form class="card shadow-sm" method="GET" action="{{ route('reports.members') }}">
    <div class="card-body">
      <div class="row g-3 align-items-end">
        <div class="col-md-4">
          <label class="form-label" for="start_date">Tanggal Mulai</label>
          <input id="start_date" type="date" name="start_date" value="{{ $startDate }}" class="form-control">
        </div>
        <div class="col-md-4">
          <label class="form-label" for="end_date">Tanggal Selesai</label>
          <input id="end_date" type="date" name="end_date" value="{{ $endDate }}" class="form-control">
        </div>
        <div class="col-md-4">
          <button class="btn btn-primary w-100" type="submit">Filter</button>
        </div>
      </div>
    </div>
  </form>

  <div class="row g-3">
    <div class="col-md-6">
      <div class="card shadow-sm h-100">
        <div class="card-body">
          <div class="text-body-secondary small">Total Anggota Aktif</div>
          <div class="fs-4 fw-bold text-success">{{ $report['active_members'] ?? 0 }}</div>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card shadow-sm h-100">
        <div class="card-body">
          <div class="text-body-secondary small">Anggota pada Periode Ini</div>
          <div class="fs-4 fw-bold">{{ $report['new_members'] ?? 0 }}</div>
        </div>
      </div>
    </div>
  </div>

  <section class="card shadow-sm">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>Kode</th>
            <th>Nama</th>
            <th>Tipe</th>
            <th>Kelas</th>
            <th>Kontak</th>
            <th>Tanggal Bergabung</th>
          </tr>
        </thead>
        <tbody>
          @forelse($members as $member)
            <tr>
              <td class="font-monospace small">{{ $member->member_code }}</td>
              <td><a href="{{ route('members.show', $member->id) }}">{{ $member->name }}</a></td>
              <td>{{ $member->memberType?->name ?? '-' }}</td>
              <td>{{ $member->class?->name ?? '-' }}</td>
              <td>{{ $member->phone ?: ($member->email ?: '-') }}</td>
              <td>{{ $member->join_date?->format('d/m/Y') ?? $member->created_at?->format('d/m/Y') ?? '-' }}</td>
            </tr>
          @empty
            <tr><td colspan="6" class="text-center text-body-secondary py-4">Tidak ada anggota aktif pada periode ini.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="card-footer d-flex justify-content-end">{{ $members->links() }}</div>
  </section>
</div>
@endsection
