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
  </div>
  <div class="col-md-6">
    <label class="form-label" for="barcode">Barcode</label>
    <input id="barcode" class="form-control" name="barcode" value="{{ old('barcode', $currentCopy->barcode ?? '') }}" required>
  </div>
  <div class="col-md-4">
    <label class="form-label" for="location">Lokasi</label>
    <input id="location" class="form-control" name="location" value="{{ old('location', $currentCopy->location ?? '') }}">
  </div>
  <div class="col-md-4">
    <label class="form-label" for="status_id">Status</label>
    <select id="status_id" name="status_id" class="form-select">
      <option value="">Pilih status</option>
      @foreach($statuses as $status)
        <option value="{{ $status->id }}" @selected(old('status_id', $currentCopy->status_id ?? '') == $status->id)>{{ $status->name }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-4">
    <label class="form-label" for="condition_id">Kondisi</label>
    <select id="condition_id" name="condition_id" class="form-select">
      <option value="">Pilih kondisi</option>
      @foreach($conditions as $condition)
        <option value="{{ $condition->id }}" @selected(old('condition_id', $currentCopy->condition_id ?? '') == $condition->id)>{{ $condition->name }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-4">
    <label class="form-label" for="acquisition_date">Tanggal Akuisisi</label>
    <input id="acquisition_date" type="date" class="form-control" name="acquisition_date" value="{{ old('acquisition_date', optional($currentCopy?->acquisition_date)->format('Y-m-d')) }}">
  </div>
  <div class="col-md-8">
    <label class="form-label" for="notes">Catatan</label>
    <input id="notes" class="form-control" name="notes" value="{{ old('notes', $currentCopy->notes ?? '') }}">
  </div>
</div>
<div class="d-flex justify-content-end gap-2 border-top pt-3 mt-4">
  <a class="btn btn-outline-secondary" href="{{ route('copies.index') }}">Batal</a>
  <button class="btn btn-primary" type="submit">Simpan</button>
</div>
