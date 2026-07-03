@extends('layouts.app')

@section('content')
  <div class="page-stack">
    <section class="page-header">
      <div>
        <div class="breadcrumb">SIPUS / Peminjaman</div>
        <h1>Daftar Peminjaman</h1>
        <p>Kelola transaksi peminjaman dan status buku kembali.</p>
      </div>
      <div class="page-actions">
        <a class="btn btn-primary" href="{{ route('loans.borrow') }}">Tambah Peminjaman</a>
      </div>
    </section>
    <section class="panel table-panel">
      <div class="panel-header">
        <h2>Riwayat Peminjaman</h2>
      </div>
      <div class="table-responsive">
        <table class="data-table">
          <thead>
            <tr>
              <th>Kode</th>
              <th>Anggota</th>
              <th>Buku</th>
              <th>Tgl Pinjam</th>
              <th>Tgl Kembali</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <tr><td>TRX-001</td><td>Aulia</td><td>Sejarah Islam</td><td>01 Jul 2026</td><td>08 Jul 2026</td><td>Dipinjam</td></tr>
            <tr><td>TRX-002</td><td>Rama</td><td>Biologi</td><td>27 Jun 2026</td><td>04 Jul 2026</td><td>Dikembalikan</td></tr>
          </tbody>
        </table>
      </div>
    </section>
  </div>
@endsection
