@extends('layouts.app')

@section('content')
  <div class="page-stack">
    <section class="page-header">
      <div>
        <div class="breadcrumb">SIPUS / Sirkulasi / Anggota</div>
        <h1>Anggota Perpustakaan</h1>
        <p>{{ number_format($members->total(), 0, ',', '.') }} anggota terdaftar</p>
      </div>
      <div class="page-actions">
        <button class="btn btn-secondary" type="button">Import Excel</button>
        <button class="btn btn-primary" type="button">Tambah Anggota</button>
      </div>
    </section>

    <section class="summary-row">
      <div class="summary-card blue">
        <strong>{{ number_format($members->total(), 0, ',', '.') }}</strong>
        <span>Total Anggota</span>
      </div>
      <div class="summary-card purple">
        <strong>{{ $members->where('is_active', true)->count() }}</strong>
        <span>Aktif di halaman ini</span>
      </div>
      <div class="summary-card amber">
        <strong>{{ $members->count() }}</strong>
        <span>Ditampilkan</span>
      </div>
    </section>

    <section class="panel table-panel">
      <div class="toolbar">
        <label class="search-field">
          <span>⌕</span>
          <input type="search" placeholder="Cari nama, NIS, kode anggota...">
        </label>
        <button class="btn btn-secondary" type="button">Filter</button>
      </div>

      <div class="table-responsive">
        <table class="data-table">
          <thead>
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
                <td><span class="status success">{{ ($member->is_active ?? true) ? 'Aktif' : 'Nonaktif' }}</span></td>
                <td><a class="table-action" href="{{ route('members.show', $member->id) }}">Detail</a></td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="empty-state">Belum ada anggota yang tercatat.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <div class="panel-footer">
        <span>Menampilkan {{ $members->count() }} dari {{ $members->total() }} data</span>
        <div>{{ $members->links() }}</div>
      </div>
    </section>
  </div>
@endsection
