@extends('layouts.app')

@section('content')
  <div class="page-stack">
    <section class="page-header">
      <div>
        <div class="breadcrumb"><a href="{{ route('inventory.index') }}">Inventory</a> / Pengadaan</div>
        <h1>Daftar Pengadaan</h1>
        <p>Riwayat permintaan pembelian buku.</p>
      </div>
      <a class="btn btn-primary" href="{{ route('procurements.create') }}">Tambah Pengadaan</a>
    </section>

    <section class="panel">
      @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
      <table class="table">
        <thead><tr><th>ID</th><th>Supplier</th><th>Tanggal</th><th>Total</th><th>Status</th><th>Aksi</th></tr></thead>
        <tbody>
          @foreach($orders as $o)
            <tr>
              <td>{{ $o->id }}</td>
              <td>{{ $o->supplier_name }}</td>
              <td>{{ $o->order_date->format('Y-m-d') }}</td>
              <td>{{ number_format($o->total,2) }}</td>
              <td>{{ $o->status }}</td>
              <td>
                <a class="btn btn-sm" href="{{ route('procurements.show', $o->id) }}">Lihat</a>
                <form action="{{ route('procurements.destroy', $o->id) }}" method="post" style="display:inline">
                  @csrf @method('delete')
                  <button class="btn btn-sm btn-danger">Hapus</button>
                </form>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
      {{ $orders->links() }}
    </section>
  </div>
@endsection
