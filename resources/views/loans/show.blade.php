@extends('layouts.app')

@section('content')
@php
  $statusBadge = match($transaction->status) {
    'borrowed' => 'primary',
    'partially_returned' => 'warning',
    'returned' => 'success',
    default => 'danger',
  };
@endphp
<div class="d-flex flex-column gap-3">
  <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
    <div>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-1">
          <li class="breadcrumb-item"><a href="{{ route('loans.index') }}">Peminjaman</a></li>
          <li class="breadcrumb-item active">Detail</li>
        </ol>
      </nav>
      <h1 class="h3 mb-1">Detail Peminjaman</h1>
      <p class="text-body-secondary mb-0">{{ $transaction->transaction_code }}</p>
    </div>
    <div class="d-flex gap-2">
      <a href="{{ route('reports.fines') }}" class="btn btn-outline-primary">Laporan Denda</a>
      <a href="{{ route('loans.index') }}" class="btn btn-outline-secondary">Kembali</a>
    </div>
  </div>

  <div class="card shadow-sm">
    <div class="card-header bg-white d-flex flex-wrap align-items-center justify-content-between gap-2">
      <div>
        <div class="text-body-secondary small">Kode Transaksi</div>
        <div class="fw-semibold">{{ $transaction->transaction_code }}</div>
      </div>
      <span class="badge text-bg-{{ $statusBadge }}">{{ str_replace('_', ' ', ucfirst($transaction->status)) }}</span>
    </div>

    <div class="card-body">
      <h2 class="h5">Informasi Anggota</h2>
      <div class="row g-3 mb-4">
        <div class="col-md-3"><div class="text-body-secondary small">Nama</div><div class="fw-semibold">{{ $transaction->member_name_snapshot }}</div></div>
        <div class="col-md-3"><div class="text-body-secondary small">Kode Anggota</div><div class="fw-semibold">{{ $transaction->member_code_snapshot }}</div></div>
        <div class="col-md-3"><div class="text-body-secondary small">Kelas</div><div class="fw-semibold">{{ $transaction->member_class_snapshot ?? '-' }}</div></div>
        <div class="col-md-3"><div class="text-body-secondary small">Tipe Anggota</div><div class="fw-semibold">{{ $transaction->member_type_snapshot ?? '-' }}</div></div>
      </div>

      <h2 class="h5">Informasi Peminjaman</h2>
      <div class="row g-3">
        <div class="col-md-3"><div class="text-body-secondary small">Tanggal Pinjam</div><div class="fw-semibold">{{ $transaction->borrow_date->format('d/m/Y') }}</div></div>
        <div class="col-md-3"><div class="text-body-secondary small">Jatuh Tempo</div><div class="fw-semibold">{{ $transaction->due_date->format('d/m/Y') }}</div></div>
        <div class="col-md-3"><div class="text-body-secondary small">Durasi</div><div class="fw-semibold">{{ $transaction->borrow_date->diffInDays($transaction->due_date) }} hari</div></div>
        <div class="col-md-3">
          <div class="text-body-secondary small">Status Waktu</div>
          <div class="fw-semibold {{ now()->gt($transaction->due_date) && $transaction->status !== 'returned' ? 'text-danger' : 'text-success' }}">
            {{ now()->gt($transaction->due_date) && $transaction->status !== 'returned' ? now()->diffInDays($transaction->due_date) . ' hari terlambat' : 'Tepat waktu' }}
          </div>
        </div>
      </div>
    </div>

    <div class="table-responsive border-top">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>Judul Buku</th>
            <th>Kode Inventaris</th>
            <th>Jatuh Tempo</th>
            <th>Status</th>
            <th>Tanggal Kembali</th>
            <th class="text-end">Denda</th>
          </tr>
        </thead>
        <tbody>
          @foreach($transaction->borrowingItems as $item)
            <tr>
              <td>{{ $item->book_title_snapshot }}</td>
              <td class="font-monospace small">{{ $item->inventory_code_snapshot }}</td>
              <td>{{ $item->due_date?->format('d/m/Y') ?? '-' }}</td>
              <td><span class="badge text-bg-{{ $item->status === 'returned' ? 'success' : 'primary' }}">{{ ucfirst($item->status) }}</span></td>
              <td>{{ $item->return_date?->format('d/m/Y') ?? '-' }}</td>
              <td class="text-end">Rp {{ number_format($item->fine_amount ?? 0, 0, ',', '.') }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

  @if($transaction->fines->count() > 0)
    <div class="card shadow-sm">
      <div class="card-header bg-white"><h2 class="h5 mb-0">Daftar Denda</h2></div>
      <div class="list-group list-group-flush">
        @foreach($transaction->fines as $fine)
          <div class="list-group-item d-flex align-items-center justify-content-between gap-3">
            <div>
              <div class="fw-semibold">{{ $fine->reason }}</div>
              <div class="text-body-secondary small">
                Rp {{ number_format($fine->amount, 0, ',', '.') }}
                @if($fine->status === 'paid' && $fine->paid_date)
                  - lunas {{ $fine->paid_date->format('d/m/Y') }}
                @endif
              </div>
            </div>
            <div class="d-flex align-items-center gap-2">
              <span class="badge text-bg-{{ $fine->status === 'paid' ? 'success' : ($fine->status === 'waived' ? 'secondary' : 'danger') }}">
                {{ $fine->status === 'paid' ? 'Lunas' : ($fine->status === 'waived' ? 'Dihapus' : 'Belum Lunas') }}
              </span>
              @if($fine->status === 'unpaid')
                <form method="POST" action="{{ route('fines.paid', $fine->id) }}" data-confirm="Tandai denda ini sudah lunas?">
                  @csrf
                  <button class="btn btn-sm btn-outline-success" type="submit">Tandai Lunas</button>
                </form>
              @endif
            </div>
          </div>
        @endforeach
      </div>
    </div>
  @endif
</div>
@endsection
