@extends('layouts.app')

@section('content')
  <div class="page-stack">
    <section class="page-header">
      <div>
        <div class="breadcrumb">SIPUS / Eksemplar</div>
        <h1>Eksemplar Buku</h1>
        <p>Kelola inventaris fisik buku dan status barcodes.</p>
      </div>
      <div class="page-actions">
        <a class="btn btn-primary" href="{{ route('copies.index') }}">Kelola Eksamplar</a>
        <a class="btn btn-secondary" href="{{ route('procurements.index') }}">Kelola Pengadaan</a>
      </div>
    </section>
    <section class="panel table-panel">
      <div class="panel-header">
        <h2>Daftar Eksemplar</h2>
      </div>
      <div class="table-responsive">
        <table class="data-table">
          <thead>
            <tr>
              <th>Kode Inventaris</th>
              <th>Buku</th>
              <th>Rak</th>
              <th>Kondisi</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <tr><td>INV0001</td><td>Matematika Dasar</td><td>RAK-A1</td><td>Baik</td><td>Tersedia</td></tr>
            <tr><td>INV0002</td><td>Fisika Modern</td><td>RAK-B2</td><td>Baik</td><td>Dipinjam</td></tr>
          </tbody>
        </table>
      </div>
    </section>
  </div>
@endsection
