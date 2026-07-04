@extends('layouts.app')

@section('content')
  <div class="page-stack">
    <section class="page-header">
      <div>
        <div class="breadcrumb">SIPUS / Pengadaan</div>
        <h1>Pengadaan Buku</h1>
        <p>Catat proses pembelian dan pemasukan buku baru.</p>
      </div>
      <div class="page-actions">
        <a class="btn btn-primary" href="{{ route('procurements.create') }}">Tambah Pengadaan</a>
      </div>
    </section>
    <section class="panel table-panel">
      <div class="panel-header">
        <h2>Daftar Pengadaan</h2>
        <a href="{{ route('procurements.index') }}">Buka modul pengadaan</a>
      </div>
      <div class="table-responsive">
        <table class="data-table">
          <thead>
            <tr>
              <th>Supplier</th>
              <th>Tanggal</th>
              <th>Status</th>
              <th>Total</th>
              <th>Item</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($procurements as $procurement)
              <tr>
                <td><strong>{{ $procurement->supplier_name }}</strong></td>
                <td>{{ optional($procurement->order_date)->format('d/m/Y') ?? '-' }}</td>
                <td><span class="status info">{{ ucfirst($procurement->status) }}</span></td>
                <td>Rp {{ number_format($procurement->total ?? 0, 0, ',', '.') }}</td>
                <td>{{ $procurement->items->count() }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="empty-state">Belum ada pengadaan.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      {{ $procurements->links() }}
    </section>
  </div>
@endsection
