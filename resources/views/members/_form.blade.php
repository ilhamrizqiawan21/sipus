@csrf
<div class="row g-3">
  <div class="col-md-4">
    <label class="form-label" for="member_code">Kode Anggota</label>
    <input class="form-control @error('member_code') is-invalid @enderror" id="member_code" name="member_code" value="{{ old('member_code', $member->member_code ?? '') }}" required>
    @error('member_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-8">
    <label class="form-label" for="name">Nama</label>
    <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $member->name ?? '') }}" required>
    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-4">
    <label class="form-label" for="nis_nisn">NIS/NISN</label>
    <input class="form-control" id="nis_nisn" name="nis_nisn" value="{{ old('nis_nisn', $member->nis_nisn ?? '') }}">
  </div>
  <div class="col-md-4">
    <label class="form-label" for="nip">NIP</label>
    <input class="form-control" id="nip" name="nip" value="{{ old('nip', $member->nip ?? '') }}">
  </div>
  <div class="col-md-4">
    <label class="form-label" for="gender">Jenis Kelamin</label>
    <select class="form-select" id="gender" name="gender">
      <option value="">Pilih</option>
      <option value="M" @selected(old('gender', $member->gender ?? '') === 'M')>Laki-laki</option>
      <option value="F" @selected(old('gender', $member->gender ?? '') === 'F')>Perempuan</option>
    </select>
  </div>
  <div class="col-md-4">
    <label class="form-label" for="phone">Telepon</label>
    <input class="form-control" id="phone" name="phone" value="{{ old('phone', $member->phone ?? '') }}">
  </div>
  <div class="col-md-4">
    <label class="form-label" for="email">Email</label>
    <input class="form-control" id="email" type="email" name="email" value="{{ old('email', $member->email ?? '') }}">
  </div>
  <div class="col-md-4">
    <label class="form-label" for="join_date">Tanggal Bergabung</label>
    <input class="form-control" id="join_date" type="date" name="join_date" value="{{ old('join_date', optional($member->join_date ?? null)->format('Y-m-d') ?? date('Y-m-d')) }}">
  </div>
  <div class="col-md-4">
    <label class="form-label" for="birth_date">Tanggal Lahir</label>
    <input class="form-control" id="birth_date" type="date" name="birth_date" value="{{ old('birth_date', optional($member->birth_date ?? null)->format('Y-m-d')) }}">
  </div>
  <div class="col-md-4">
    <label class="form-label" for="card_number">Nomor Kartu</label>
    <input class="form-control" id="card_number" name="card_number" value="{{ old('card_number', $member->card_number ?? '') }}">
  </div>
  <div class="col-md-12">
    <label class="form-label" for="address">Alamat</label>
    <textarea class="form-control" id="address" name="address" rows="3">{{ old('address', $member->address ?? '') }}</textarea>
  </div>
  <div class="col-12">
    <div class="form-check form-switch">
      <input type="hidden" name="is_active" value="0">
      <input class="form-check-input" id="is_active" type="checkbox" name="is_active" value="1" @checked(old('is_active', $member->is_active ?? true))>
      <label class="form-check-label" for="is_active">Anggota aktif</label>
    </div>
  </div>
</div>
<div class="d-flex gap-2 justify-content-end border-top pt-3 mt-4">
  <a class="btn btn-outline-secondary" href="{{ route('members.index') }}">Batal</a>
  <button class="btn btn-primary" type="submit">Simpan</button>
</div>
