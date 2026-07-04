@extends('layouts.app')

@section('content')
<div class="d-flex flex-column gap-3">
  <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
    <div>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-1">
          <li class="breadcrumb-item"><a href="{{ route('loans.index') }}">Peminjaman</a></li>
          <li class="breadcrumb-item active">Pengembalian</li>
        </ol>
      </nav>
      <h1 class="h3 mb-1">Pencatatan Pengembalian</h1>
      <p class="text-body-secondary mb-0">Pilih transaksi aktif dan buku yang dikembalikan.</p>
    </div>
    <a href="{{ route('loans.index') }}" class="btn btn-outline-secondary">Kembali</a>
  </div>

  @if($errors->any())
    <div class="alert alert-danger mb-0">
      <ul class="mb-0">
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="row g-3">
    <div class="col-lg-5">
      <div class="card shadow-sm h-100">
        <div class="card-header bg-white">
          <h2 class="h5 mb-0">Transaksi Aktif</h2>
        </div>
        <div class="list-group list-group-flush transaction-list">
          @forelse($transactions as $t)
            <button class="list-group-item list-group-item-action text-start" type="button" onclick="selectTransaction({{ $t->id }}, this)">
              <span class="d-block fw-semibold">{{ $t->member_name_snapshot }}</span>
              <span class="d-block text-body-secondary small">{{ $t->transaction_code }} - jatuh tempo {{ $t->due_date->format('d/m/Y') }}</span>
            </button>
          @empty
            <div class="list-group-item text-body-secondary">Tidak ada peminjaman aktif.</div>
          @endforelse
        </div>
      </div>
    </div>
    <div class="col-lg-7">
      <div class="card shadow-sm">
        <div class="card-body">
          <form action="" method="POST" id="return-form">
            @csrf
            <input type="hidden" id="transaction-id" name="transaction_id">
            <div class="mb-3">
              <label class="form-label">Daftar Buku</label>
              <div id="items-container" class="border rounded p-3 text-body-secondary">Pilih transaksi terlebih dahulu.</div>
            </div>
            <div class="mb-3">
              <label class="form-label" for="return_date">Tanggal Pengembalian</label>
              <input id="return_date" type="date" name="return_date" value="{{ date('Y-m-d') }}" class="form-control" required>
            </div>
            <div class="d-flex justify-content-end gap-2 border-top pt-3">
              <a href="{{ route('loans.index') }}" class="btn btn-outline-secondary">Batal</a>
              <button type="submit" class="btn btn-success">Proses Pengembalian</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
const transactions = @json($transactions->toArray());
function selectTransaction(id, element) {
  document.querySelectorAll('.transaction-list .list-group-item').forEach(item => item.classList.remove('active'));
  element.classList.add('active');
  document.getElementById('transaction-id').value = id;
  const tx = transactions.find(item => item.id == id);
  if (!tx) return;

  const items = (tx.borrowing_items || []).filter(item => item.status !== 'returned');
  const html = items.length
    ? items.map(item => `
      <label class="form-check mb-2">
        <input class="form-check-input" type="checkbox" name="book_copy_ids[]" value="${item.book_copy_id}">
        <span class="form-check-label">${item.book_title_snapshot}</span>
      </label>
    `).join('')
    : '<div class="text-body-secondary">Semua item pada transaksi ini sudah dikembalikan.</div>';

  document.getElementById('items-container').innerHTML = html;
  document.getElementById('return-form').action = `/loans/${id}/return`;
}
</script>
@endsection
