@extends('layouts.app')

@section('content')
  <div class="d-flex flex-column gap-3">
    <section class="d-flex flex-wrap align-items-center justify-content-between gap-3">
      <div>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-1">
            <li class="breadcrumb-item"><a href="{{ route('procurements.index') }}">Pengadaan</a></li>
            <li class="breadcrumb-item active">Tambah</li>
          </ol>
        </nav>
        <h1 class="h3 mb-1">Tambah Pengadaan</h1>
      </div>
      <a class="btn btn-outline-secondary" href="{{ route('procurements.index') }}">Batal</a>
    </section>

    @if($errors->any())
      <div class="alert alert-danger mb-0">
        <ul class="mb-0">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <section class="card shadow-sm">
      <div class="card-body">
        <form method="post" action="{{ route('procurements.store') }}">
          @csrf
          <div class="row g-3">
            <div class="col-md-8">
              <label class="form-label" for="supplier_name">Supplier</label>
              <input id="supplier_name" class="form-control" type="text" name="supplier_name" value="{{ old('supplier_name') }}" required>
            </div>
            <div class="col-md-4">
              <label class="form-label" for="order_date">Tanggal Pesan</label>
              <input id="order_date" class="form-control" type="date" name="order_date" value="{{ old('order_date', date('Y-m-d')) }}" required>
            </div>
            <div class="col-12">
              <label class="form-label">Items</label>
              <div id="procurement-items" class="vstack gap-2">
                <div class="row g-2 procurement-item">
                  <div class="col-md-6">
                    <select name="book_id[]" class="form-select" required>
                      <option value="">Pilih buku</option>
                      @foreach($books as $book)
                        <option value="{{ $book->id }}">{{ $book->title }}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="col-md-2"><input class="form-control" type="number" name="quantity[]" min="1" value="1" placeholder="Qty" required></div>
                  <div class="col-md-3"><input class="form-control" type="number" name="unit_price[]" min="0" value="0" placeholder="Harga" required></div>
                  <div class="col-md-1 d-grid"><button class="btn btn-outline-secondary" type="button" onclick="addProcurementItem()">+</button></div>
                </div>
              </div>
            </div>
            <div class="col-12">
              <label class="form-label" for="notes">Catatan</label>
              <textarea id="notes" class="form-control" name="notes" rows="3">{{ old('notes') }}</textarea>
            </div>
          </div>
          <div class="d-flex justify-content-end gap-2 border-top pt-3 mt-4">
            <a class="btn btn-outline-secondary" href="{{ route('procurements.index') }}">Batal</a>
            <button class="btn btn-primary" type="submit">Simpan</button>
          </div>
        </form>
      </div>
    </section>
  </div>
  <script>
    function addProcurementItem() {
      const container = document.getElementById('procurement-items');
      const first = container.querySelector('.procurement-item');
      const clone = first.cloneNode(true);
      clone.querySelectorAll('select, input').forEach((field) => {
        field.value = field.tagName === 'SELECT' ? '' : (field.name === 'quantity[]' ? 1 : 0);
      });
      clone.querySelector('button').textContent = '-';
      clone.querySelector('button').className = 'btn btn-outline-danger';
      clone.querySelector('button').onclick = () => clone.remove();
      container.appendChild(clone);
    }
  </script>
@endsection
