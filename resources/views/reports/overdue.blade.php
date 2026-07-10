@extends('layouts.app')

@section('content')
<div class="d-flex flex-column gap-3">
  <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
    <div>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-1">
          <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Laporan</a></li>
          <li class="breadcrumb-item active">Buku Terlambat</li>
        </ol>
      </nav>
      <h1 class="h3 mb-1">Laporan Buku Terlambat</h1>
      <p class="text-body-secondary mb-0">Eksemplar yang belum kembali dan sudah melewati tanggal jatuh tempo.</p>
    </div>
    <a href="{{ route('reports.index', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="btn btn-outline-secondary">Kembali</a>
  </div>

  <form class="card shadow-sm" method="GET" action="{{ route('reports.overdue') }}">
    <div class="card-body">
      <div class="row g-3 align-items-end">
        <div class="col-md-4">
          <label class="form-label" for="start_date">Jatuh Tempo Mulai</label>
          <input id="start_date" type="date" name="start_date" value="{{ $startDate }}" class="form-control">
        </div>
        <div class="col-md-4">
          <label class="form-label" for="end_date">Jatuh Tempo Selesai</label>
          <input id="end_date" type="date" name="end_date" value="{{ $endDate }}" class="form-control">
        </div>
        <div class="col-md-4">
          <button class="btn btn-primary w-100" type="submit">Filter</button>
        </div>
      </div>
    </div>
  </form>

  <div class="card shadow-sm">
    <div class="card-body d-flex justify-content-between align-items-center">
      <span class="text-body-secondary">Total keterlambatan aktif</span>
      <strong class="fs-4 text-danger">{{ $report['total_overdue'] ?? 0 }}</strong>
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
            <th>Jatuh Tempo</th>
            <th class="text-end">Terlambat</th>
          </tr>
        </thead>
        <tbody>
          @forelse($items as $item)
            @php($transaction = $item->borrowingTransaction)
            @php($daysLate = $item->due_date ? $item->due_date->diffInDays(now()) : 0)
            <tr>
              <td><a href="{{ route('loans.show', $transaction->id) }}">{{ $transaction->transaction_code }}</a></td>
              <td>{{ $transaction->member_name_snapshot }}</td>
              <td>{{ $item->book_title_snapshot }}</td>
              <td class="font-monospace small">{{ $item->inventory_code_snapshot }}</td>
              <td>{{ $item->due_date?->format('d/m/Y') ?? '-' }}</td>
              <td class="text-end"><span class="badge text-bg-danger">{{ $daysLate }} hari</span></td>
            </tr>
          @empty
            <tr><td colspan="6" class="text-center text-body-secondary py-4">Tidak ada buku terlambat pada periode ini.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="card-footer d-flex justify-content-end">{{ $items->links() }}</div>
  </section>
</div>
@endsection
