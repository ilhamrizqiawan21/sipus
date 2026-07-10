@extends('layouts.app')

@section('content')
<div class="d-flex flex-column gap-3">
  <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
    <div>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-1">
          <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Laporan</a></li>
          <li class="breadcrumb-item active">Denda</li>
        </ol>
      </nav>
      <h1 class="h3 mb-1">Laporan Denda</h1>
      <p class="text-body-secondary mb-0">Ringkasan denda keterlambatan dan status pembayaran.</p>
    </div>
    <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary">Kembali</a>
  </div>

  @if(session('success'))
    <div class="alert alert-success mb-0">{{ session('success') }}</div>
  @endif

  <form class="card shadow-sm" method="GET" action="{{ route('reports.fines') }}">
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
    <div class="col-md-3">
      <div class="card shadow-sm h-100">
        <div class="card-body">
          <div class="text-body-secondary small">Total Denda</div>
          <div class="fs-5 fw-bold">Rp {{ number_format($report['total_fines'] ?? 0, 0, ',', '.') }}</div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card shadow-sm h-100">
        <div class="card-body">
          <div class="text-body-secondary small">Belum Lunas</div>
          <div class="fs-5 fw-bold text-danger">Rp {{ number_format($report['unpaid_fines'] ?? 0, 0, ',', '.') }}</div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card shadow-sm h-100">
        <div class="card-body">
          <div class="text-body-secondary small">Sudah Lunas</div>
          <div class="fs-5 fw-bold text-success">Rp {{ number_format($report['paid_fines'] ?? 0, 0, ',', '.') }}</div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card shadow-sm h-100">
        <div class="card-body">
          <div class="text-body-secondary small">Jumlah Catatan</div>
          <div class="fs-5 fw-bold">{{ $report['total_records'] ?? 0 }}</div>
        </div>
      </div>
    </div>
  </div>

  <section class="card shadow-sm">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>Tanggal</th>
            <th>Transaksi</th>
            <th>Anggota</th>
            <th>Buku</th>
            <th>Alasan</th>
            <th class="text-end">Nominal</th>
            <th>Status</th>
            <th class="text-end">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($fines as $fine)
            @php($item = $fine->borrowingItem)
            @php($transaction = $item?->borrowingTransaction)
            <tr>
              <td>{{ $fine->created_at?->format('d/m/Y') ?? '-' }}</td>
              <td>
                @if($transaction)
                  <a href="{{ route('loans.show', $transaction->id) }}">{{ $transaction->transaction_code }}</a>
                @else
                  -
                @endif
              </td>
              <td>{{ $transaction?->member_name_snapshot ?? '-' }}</td>
              <td>{{ $item?->book_title_snapshot ?? '-' }}</td>
              <td>{{ $fine->reason ?? ucfirst($fine->fine_type) }}</td>
              <td class="text-end">Rp {{ number_format($fine->amount, 0, ',', '.') }}</td>
              <td>
                <span class="badge text-bg-{{ $fine->status === 'paid' ? 'success' : ($fine->status === 'waived' ? 'secondary' : 'danger') }}">
                  {{ $fine->status === 'paid' ? 'Lunas' : ($fine->status === 'waived' ? 'Dihapus' : 'Belum Lunas') }}
                </span>
              </td>
              <td class="text-end">
                @if($fine->status === 'unpaid')
                  <form method="POST" action="{{ route('fines.paid', $fine->id) }}" data-confirm="Tandai denda ini sudah lunas?">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-success">Tandai Lunas</button>
                  </form>
                @else
                  <span class="text-body-secondary small">{{ $fine->paid_date?->format('d/m/Y') ?? '-' }}</span>
                @endif
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="text-center text-body-secondary py-4">Belum ada data denda pada periode ini.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="card-footer d-flex justify-content-end">
      {{ $fines->links() }}
    </div>
  </section>
</div>
@endsection
