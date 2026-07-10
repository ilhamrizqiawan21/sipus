@extends('layouts.app')

@section('content')
<div class="d-flex flex-column gap-3">
  <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
    <div>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-1">
          <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Laporan</a></li>
          <li class="breadcrumb-item active">Buku Dipinjam</li>
        </ol>
      </nav>
      <h1 class="h3 mb-1">Laporan Buku Dipinjam</h1>
      <p class="text-body-secondary mb-0">Daftar eksemplar yang masuk transaksi peminjaman pada periode terpilih.</p>
    </div>
    <a href="{{ route('reports.index', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="btn btn-outline-secondary">Kembali</a>
  </div>

  <form class="card shadow-sm" method="GET" action="{{ route('reports.circulation') }}">
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
    <div class="col-md-4">
      <div class="card shadow-sm h-100">
        <div class="card-body">
          <div class="text-body-secondary small">Transaksi</div>
          <div class="fs-4 fw-bold">{{ $report['total_borrowed'] ?? 0 }}</div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card shadow-sm h-100">
        <div class="card-body">
          <div class="text-body-secondary small">Eksemplar Dipinjam</div>
          <div class="fs-4 fw-bold text-primary">{{ $report['total_items_borrowed'] ?? 0 }}</div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card shadow-sm h-100">
        <div class="card-body">
          <div class="text-body-secondary small">Peminjaman Aktif</div>
          <div class="fs-4 fw-bold text-warning">{{ $report['active_loans'] ?? 0 }}</div>
        </div>
      </div>
    </div>
  </div>

  <section class="card shadow-sm">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>Transaksi</th>
            <th>Anggota</th>
            <th>Buku</th>
            <th>Kode Inventaris</th>
            <th>Tgl Pinjam</th>
            <th>Jatuh Tempo</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          @forelse($items as $item)
            @php($transaction = $item->borrowingTransaction)
            <tr>
              <td><a href="{{ route('loans.show', $transaction->id) }}">{{ $transaction->transaction_code }}</a></td>
              <td>{{ $transaction->member_name_snapshot }}</td>
              <td>{{ $item->book_title_snapshot }}</td>
              <td class="font-monospace small">{{ $item->inventory_code_snapshot }}</td>
              <td>{{ $transaction->borrow_date?->format('d/m/Y') ?? '-' }}</td>
              <td>{{ $item->due_date?->format('d/m/Y') ?? '-' }}</td>
              <td><span class="badge text-bg-{{ $item->status === 'returned' ? 'success' : 'primary' }}">{{ ucfirst($item->status) }}</span></td>
            </tr>
          @empty
            <tr><td colspan="7" class="text-center text-body-secondary py-4">Belum ada peminjaman pada periode ini.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="card-footer d-flex justify-content-end">{{ $items->links() }}</div>
  </section>
</div>
@endsection
