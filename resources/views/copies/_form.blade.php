@php($currentCopy = $copy ?? null)
<div class="row g-3">
  <div class="col-md-6">
    <label class="form-label" for="book_id">Buku</label>
    <select id="book_id" name="book_id" class="form-select" required>
      <option value="">Pilih buku</option>
      @foreach($books as $b)
        <option value="{{ $b->id }}" @selected(old('book_id', $currentCopy->book_id ?? '') == $b->id)>{{ $b->title }}</option>
      @endforeach
    </select>
    @error('book_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-3">
    <label class="form-label" for="inventory_code">Kode Inventaris</label>
    <input id="inventory_code" class="form-control" name="inventory_code" value="{{ old('inventory_code', $currentCopy->inventory_code ?? '') }}" required>
    @error('inventory_code')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-3">
    <label class="form-label" for="barcode">Barcode</label>
    <input id="barcode" class="form-control" name="barcode" value="{{ old('barcode', $currentCopy->barcode ?? '') }}">
    @error('barcode')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
  </div>

  <div class="col-md-3">
    <label class="form-label" for="status_id">Status</label>
    <select id="status_id" name="status_id" class="form-select" required>
      <option value="">Pilih status</option>
      @foreach($statuses as $status)
        <option value="{{ $status->id }}" @selected(old('status_id', $currentCopy->status_id ?? $defaultStatusId) == $status->id)>{{ $status->name }}</option>
      @endforeach
    </select>
    @error('status_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-3">
    <label class="form-label" for="condition_id">Kondisi</label>
    <select id="condition_id" name="condition_id" class="form-select" required>
      <option value="">Pilih kondisi</option>
      @foreach($conditions as $condition)
        <option value="{{ $condition->id }}" @selected(old('condition_id', $currentCopy->condition_id ?? $defaultConditionId) == $condition->id)>{{ $condition->name }}</option>
      @endforeach
    </select>
    @error('condition_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-3">
    <label class="form-label" for="source_id">Sumber</label>
    <select id="source_id" name="source_id" class="form-select" required>
      <option value="">Pilih sumber</option>
      @foreach($sources as $source)
        <option value="{{ $source->id }}" @selected(old('source_id', $currentCopy->source_id ?? $defaultSourceId) == $source->id)>{{ $source->name }}</option>
      @endforeach
    </select>
    @error('source_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-3">
    <label class="form-label" for="bookshelf_id">Rak</label>
    <select id="bookshelf_id" name="bookshelf_id" class="form-select">
      <option value="">Belum ditempatkan</option>
      @foreach($bookshelves as $bookshelf)
        <option value="{{ $bookshelf->id }}" @selected(old('bookshelf_id', $currentCopy->bookshelf_id ?? '') == $bookshelf->id)>{{ $bookshelf->code }} - {{ $bookshelf->name }}</option>
      @endforeach
    </select>
    @error('bookshelf_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
  </div>

  <div class="col-md-3">
    <label class="form-label" for="acquisition_date">Tanggal Akuisisi</label>
    <input id="acquisition_date" type="date" class="form-control" name="acquisition_date" value="{{ old('acquisition_date', optional($currentCopy?->acquisition_date)->format('Y-m-d')) }}">
    @error('acquisition_date')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-3">
    <label class="form-label" for="acquisition_price">Harga Akuisisi</label>
    <input id="acquisition_price" type="number" class="form-control" name="acquisition_price" value="{{ old('acquisition_price', $currentCopy->acquisition_price ?? '') }}" min="0" step="0.01">
    @error('acquisition_price')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-6">
    <label class="form-label" for="notes">Catatan</label>
    <input id="notes" class="form-control" name="notes" value="{{ old('notes', $currentCopy->notes ?? '') }}">
    @error('notes')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
  </div>
</div>
<div class="d-flex justify-content-end gap-2 border-top pt-3 mt-4">
  <a class="btn btn-outline-secondary" href="{{ route('copies.index') }}">Batal</a>
  <button class="btn btn-primary" type="submit">Simpan</button>
</div>
