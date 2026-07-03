@extends('layouts.app')

@section('content')
  <div class="page-stack">
    <section class="page-header">
      <div>
        <div class="breadcrumb"><a href="{{ route('procurements.index') }}">Pengadaan</a> / Detail</div>
        <h1>Detail Pengadaan #{{ $order->id }}</h1>
      </div>
      <div>
        @if($order->status !== 'approved')
          <form method="post" action="{{ route('procurements.approve', $order->id) }}" style="display:inline">
            @csrf
            <button class="btn btn-success">Setujui</button>
          </form>
        @endif
        <a class="btn btn-secondary" href="{{ route('procurements.index') }}">Kembali</a>
      </div>
    </section>

    <section class="panel">
      <h3>Supplier: {{ $order->supplier_name }}</h3>
      <p>Tanggal: {{ $order->order_date->format('Y-m-d') }}</p>
      <p>Status: {{ $order->status }}</p>
      <h4>Items</h4>
      <table class="table">
        <thead><tr><th>Judul</th><th>Qty</th><th>Harga</th><th>Subtotal</th></tr></thead>
        <tbody>
          @foreach($order->items ?? [] as $it)
            <tr>
              <td>{{ $it['title'] ?? '' }}</td>
              <td>{{ $it['quantity'] ?? 0 }}</td>
              <td>{{ number_format($it['price'] ?? 0,2) }}</td>
              <td>{{ number_format(($it['quantity'] ?? 0) * ($it['price'] ?? 0),2) }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
      <p><strong>Total:</strong> {{ number_format($order->total,2) }}</p>
      <p><strong>Catatan:</strong> {{ $order->notes }}</p>
    </section>
  </div>
@endsection
