@extends('layouts.app')

@section('content')
  <div class="page-stack">
    <section class="page-header">
      <div>
        <div class="breadcrumb">SIPUS / Dashboard</div>
        <h1>Dashboard</h1>
        <p>{{ $today->translatedFormat('l, d F Y') }} - Selamat datang, {{ auth()->user()->name ?? 'Petugas' }}</p>
      </div>
      <div class="page-actions">
        <a class="btn btn-secondary" href="{{ route('reports.index') }}">Laporan</a>
        <a class="btn btn-primary" href="{{ route('loans.borrow') }}">Peminjaman Baru</a>
      </div>
    </section>

    <section class="stats-grid">
      <div class="stat-card">
        <div class="stat-icon primary">B</div>
        <div class="stat-value">{{ number_format($bookCount, 0, ',', '.') }}</div>
        <div class="stat-label">Total Koleksi</div>
        <div class="stat-subtitle">{{ number_format($bookCount, 0, ',', '.') }} judul tercatat</div>
      </div>
      <div class="stat-card">
        <div class="stat-icon teal">A</div>
        <div class="stat-value">{{ number_format($memberCount, 0, ',', '.') }}</div>
        <div class="stat-label">Anggota Aktif</div>
        <div class="stat-subtitle">Siswa, guru, dan staff</div>
      </div>
      <div class="stat-card">
        <div class="stat-icon amber">P</div>
        <div class="stat-value">{{ number_format($activeLoans, 0, ',', '.') }}</div>
        <div class="stat-label">Sedang Dipinjam</div>
        <div class="stat-subtitle">Item dalam sirkulasi</div>
      </div>
      <div class="stat-card">
        <div class="stat-icon danger">T</div>
        <div class="stat-value">{{ number_format($overdueLoans, 0, ',', '.') }}</div>
        <div class="stat-label">Buku Terlambat</div>
        <div class="stat-subtitle">Item belum dikembalikan melewati jatuh tempo</div>
      </div>
      <div class="stat-card">
        <div class="stat-icon mint">I</div>
        <div class="stat-value">{{ number_format($inventoryCount, 0, ',', '.') }}</div>
        <div class="stat-label">Total Eksemplar</div>
        <div class="stat-subtitle">Kumpulan salinan fisik buku</div>
      </div>
      <div class="stat-card">
        <div class="stat-icon secondary">D</div>
        <div class="stat-value">Rp {{ number_format($unpaidFines, 0, ',', '.') }}</div>
        <div class="stat-label">Denda Belum Lunas</div>
        <div class="stat-subtitle">Total tagihan denda aktif</div>
      </div>
    </section>

    <section class="process-steps">
      <div class="panel">
        <div class="panel-header">
          <h2>Proses Utama SIPUS</h2>
        </div>
        <div class="steps-grid">
          @foreach ($processSteps as $step)
            <div class="step-card">
              <strong>{{ $step['title'] }}</strong>
              <p>{{ $step['description'] }}</p>
            </div>
          @endforeach
        </div>
      </div>
    </section>

    <section class="dashboard-grid">
      <div class="panel wide">
        <div class="panel-header">
          <h2>Tren Peminjaman</h2>
          <span>Per bulan</span>
        </div>
        @if (count($borrowingTrend) > 0)
          <div class="bar-chart" aria-label="Grafik tren peminjaman">
            @foreach ($borrowingTrend as $trend)
            <div class="bar-group">
              <span class="bar primary" style="height: {{ max(28, $trend['total'] / 2) }}px"></span>
              <span class="bar-label">{{ $trend['month'] }}</span>
            </div>
            @endforeach
          </div>
          <div class="chart-legend">
            <span><i class="legend primary"></i> Jumlah peminjaman</span>
          </div>
        @else
          <div class="empty-state">Belum ada data peminjaman.</div>
        @endif
      </div>

      <div class="panel">
        <div class="panel-header">
          <h2>Koleksi per Kategori</h2>
        </div>
        <div class="category-list">
          @forelse ($categoryStats as $category)
            <div class="category-row">
              <span><i style="background: {{ $category['color'] }}"></i>{{ $category['label'] }}</span>
              <strong>{{ number_format($category['value'], 0, ',', '.') }}</strong>
            </div>
          @empty
            <div class="empty-state">Belum ada data kategori.</div>
          @endforelse
        </div>
      </div>
    </section>

    <section class="panel">
      <div class="panel-header">
        <h2>Aksi Cepat</h2>
      </div>
      <div class="quick-actions">
        <a href="{{ route('loans.borrow') }}">Catat Peminjaman</a>
        <a href="{{ route('loans.return') }}">Catat Pengembalian</a>
        <a href="{{ route('inventory.index') }}">Inventaris Eksemplar</a>
        <a href="{{ route('procurements.index') }}">Pengadaan Buku</a>
        <a href="{{ route('reports.index') }}">Ringkasan Laporan</a>
        <a href="{{ route('reports.fines') }}">Laporan Denda</a>
      </div>
    </section>

    <section class="panel table-panel">
      <div class="panel-header">
        <h2>Transaksi Terbaru</h2>
        <a href="{{ route('loans.index') }}">Lihat semua</a>
      </div>
      <div class="table-responsive">
        <table class="data-table">
          <thead>
            <tr>
              <th>Kode Transaksi</th>
              <th>Anggota</th>
              <th>Judul Buku</th>
              <th>Tgl Pinjam</th>
              <th>Jatuh Tempo</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($latestTransactions as $transaction)
              <tr>
                <td class="mono"><a href="{{ route('loans.show', $transaction['id']) }}">{{ $transaction['code'] }}</a></td>
                <td><strong>{{ $transaction['member'] }}</strong><small>{{ $transaction['book'] }}</small></td>
                <td>{{ $transaction['book'] }}</td>
                <td>{{ $transaction['borrowedAt'] }}</td>
                <td>{{ $transaction['dueAt'] }}</td>
                <td><span class="status {{ $transaction['status'] === 'Terlambat' ? 'danger' : 'info' }}">{{ $transaction['status'] }}</span></td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center text-muted">Belum ada transaksi.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </section>
  </div>
@endsection
