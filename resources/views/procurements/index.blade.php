@extends('layouts.app')

@section('content')
  <div class="d-flex flex-column gap-3">
    <section class="d-flex flex-wrap align-items-center justify-content-between gap-3">
      <div>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-1">
            <li class="breadcrumb-item"><a href="{{ route('inventory.index') }}">Inventaris</a></li>
            <li class="breadcrumb-item active">Pengadaan</li>
          </ol>
        </nav>
        <h1 class="h3 mb-1">Daftar Pengadaan</h1>
        <p class="text-body-secondary mb-0">Riwayat permintaan pembelian buku.</p>
      </div>
      <a class="btn btn-primary" href="{{ route('procurements.create') }}">Tambah Pengadaan</a>
    </section>

    @if(session('success'))
      <div class="alert alert-success mb-0">{{ session('success') }}</div>
    @endif

    <section class="card shadow-sm">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th>ID</th>
              <th>Supplier</th>
              <th>Tanggal</th>
              <th>Item</th>
              <th>Total</th>
              <th>Status</th>
              <th class="text-end">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($orders as $o)
              <tr>
                <td>{{ $o->id }}</td>
                <td>{{ $o->supplier_name }}</td>
                <td>{{ $o->order_date->format('Y-m-d') }}</td>
                <td>{{ $o->items_count }}</td>
                <td>Rp {{ number_format($o->total ?? 0, 0, ',', '.') }}</td>
                <td><span class="badge text-bg-{{ $o->status === 'approved' ? 'success' : 'warning' }}">{{ ucfirst($o->status) }}</span></td>
                <td class="text-end">
                  <div class="btn-group btn-group-sm">
                    <a class="btn btn-outline-primary" href="{{ route('procurements.show', $o->id) }}">Lihat</a>
                    <form action="{{ route('procurements.destroy', $o->id) }}" method="post" data-confirm="Hapus pengadaan ini?">
                      @csrf @method('delete')
                      <button class="btn btn-outline-danger rounded-start-0" type="submit">Hapus</button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr><td colspan="7" class="text-center text-body-secondary py-5">Belum ada pengadaan.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div class="card-footer d-flex justify-content-end">{{ $orders->links() }}</div>
    </section>
  </div>
@endsection
