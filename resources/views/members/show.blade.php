@extends('layouts.app')

@section('content')
  <div class="page-stack">
    <section class="page-header">
      <div>
        <div class="breadcrumb"><a href="{{ route('members.index') }}">Anggota</a> / Detail</div>
        <h1>{{ $member->name }}</h1>
        <p>{{ $member->member_code }}</p>
      </div>
      <a class="btn btn-secondary" href="{{ route('members.index') }}">Kembali</a>
    </section>

    <section class="panel">
      <div class="panel-header">
        <h2>Informasi Anggota</h2>
        <span class="status success">{{ ($member->is_active ?? true) ? 'Aktif' : 'Nonaktif' }}</span>
      </div>
      <dl class="detail-list">
        <div><dt>Kode Anggota</dt><dd>{{ $member->member_code }}</dd></div>
        <div><dt>NIS/NIP</dt><dd>{{ $member->nis_nisn ?? $member->nip ?? '-' }}</dd></div>
        <div><dt>Jenis</dt><dd>{{ $member->member_type_id ? 'Tipe #' . $member->member_type_id : 'Anggota' }}</dd></div>
        <div><dt>Telepon</dt><dd>{{ $member->phone ?? '-' }}</dd></div>
        <div><dt>Email</dt><dd>{{ $member->email ?? '-' }}</dd></div>
        <div><dt>Tanggal Bergabung</dt><dd>{{ $member->join_date ? \Illuminate\Support\Carbon::parse($member->join_date)->format('d M Y') : '-' }}</dd></div>
      </dl>
    </section>
  </div>
@endsection
