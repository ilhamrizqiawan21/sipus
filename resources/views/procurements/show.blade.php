@extends('layouts.app')

@section('content')
  <div class="d-flex flex-column gap-3">
    <section class="d-flex flex-wrap align-items-center justify-content-between gap-3">
      <div>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-1">
            <li class="breadcrumb-item"><a href="{{ route('procurements.index') }}">Pengadaan</a></li>
            <li class="breadcrumb-item active">Detail</li>
          </ol>
        </nav>
        <h1 class="h3 mb-1">Detail Pengadaan #{{ $order->id }}</h1>
      </div>
      <div class="d-flex flex-wrap gap-2">
        @if($order->status !== 'approved')
          <form method="post" action="{{ route('procurements.approve', $order->id) }}">
            @csrf
            <button class="btn btn-success">Setujui</button>
          </form>
        @endif
        <a class="btn btn-outline-secondary" href="{{ route('procurements.index') }}">Kembali</a>
      </div>
    </section>

    @if(session('success'))
      <div class="alert alert-success mb-0">{{ session('success') }}</div>
    @endif

    <section class="card shadow-sm">
      <div class="card-body">
        <div class="row g-3">
          <div class="col-md-4"><div class="text-body-secondary small">Supplier</div><div class="fw-semibold">{{ $order->supplier_name }}</div></div>
          <div class="col-md-4"><div class="text-body-secondary small">Tanggal</div><div class="fw-semibold">{{ $order->order_date->format('Y-m-d') }}</div></div>
          <div class="col-md-4"><div class="text-body-secondary small">Status</div><span class="badge text-bg-{{ $order->status === 'approved' ? 'success' : 'warning' }}">{{ ucfirst($order->status) }}</span></div>
        </div>
      </div>
      <div class="table-responsive border-top">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light"><tr><th>Buku</th><th>Qty</th><th>Harga</th><th>Subtotal</th></tr></thead>
          <tbody>
            @forelse($order->items as $it)
              <tr>
                <td>{{ $it->book->title ?? '-' }}</td>
                <td>{{ $it->quantity }}</td>
                <td>Rp {{ number_format($it->unit_price ?? 0, 0, ',', '.') }}</td>
                <td>Rp {{ number_format(($it->quantity ?? 0) * ($it->unit_price ?? 0), 0, ',', '.') }}</td>
              </tr>
            @empty
              <tr><td colspan="4" class="text-center text-body-secondary py-4">Belum ada item.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div class="card-footer d-flex flex-wrap justify-content-between gap-2">
        <span><strong>Total:</strong> Rp {{ number_format($order->total ?? 0, 0, ',', '.') }}</span>
        <span class="text-body-secondary">{{ $order->notes ?: 'Tidak ada catatan.' }}</span>
      </div>
    </section>
  </div>
@endsection
