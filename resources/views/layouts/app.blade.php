<!doctype html>
<html lang="id">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>{{ config('app.name', 'SIPUS') }}</title>
    @if (class_exists('Illuminate\Support\Facades\Vite'))
      @vite(['resources/css/app.css','resources/js/app.js'])
    @else
      <link rel="stylesheet" href="/resources/css/app.css">
      <script src="/resources/js/app.js" defer></script>
    @endif
  </head>
  <body>
    <div class="app-shell">
      <div class="app-body">
        <aside class="app-sidebar" id="sidebar">
          <div class="sidebar-brand">
            <div class="brand-mark">SA</div>
            <div>
              <div class="brand-title">SIPUS</div>
              <div class="brand-subtitle">MTs Al-Ihsan</div>
            </div>
          </div>

          <nav class="sidebar-nav" aria-label="Navigasi utama">
            <div class="nav-section">Utama</div>
            <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
              <span class="nav-icon">D</span>
              <span>Dashboard</span>
            </a>

            <div class="nav-section">Koleksi</div>
            <a class="nav-link {{ request()->routeIs('books.*') ? 'active' : '' }}" href="{{ route('books.index') }}">
              <span class="nav-icon">B</span>
              <span>Katalog Buku</span>
            </a>
            <a class="nav-link {{ request()->routeIs('inventory.*') ? 'active' : '' }}" href="{{ route('inventory.index') }}">
              <span class="nav-icon">E</span>
              <span>Eksemplar</span>
            </a>
            <a class="nav-link {{ request()->routeIs('inventory.procurement') ? 'active' : '' }}" href="{{ route('inventory.procurement') }}">
              <span class="nav-icon">P</span>
              <span>Pengadaan</span>
            </a>

            <div class="nav-section">Sirkulasi</div>
            <a class="nav-link {{ request()->routeIs('members.*') ? 'active' : '' }}" href="{{ route('members.index') }}">
              <span class="nav-icon">A</span>
              <span>Anggota</span>
            </a>
            <a class="nav-link {{ request()->routeIs('loans.index') ? 'active' : '' }}" href="{{ route('loans.index') }}">
              <span class="nav-icon">S</span>
              <span>Peminjaman</span>
              <span class="nav-badge">3</span>
            </a>
            <a class="nav-link {{ request()->routeIs('loans.return') ? 'active' : '' }}" href="{{ route('loans.return') }}">
              <span class="nav-icon">K</span>
              <span>Pengembalian</span>
            </a>

            <div class="nav-section">Sistem</div>
            <a class="nav-link {{ request()->routeIs('reports.index') ? 'active' : '' }}" href="{{ route('reports.index') }}">
              <span class="nav-icon">L</span>
              <span>Laporan</span>
            </a>
            <a class="nav-link {{ request()->routeIs('settings.index') ? 'active' : '' }}" href="{{ route('settings.index') }}">
              <span class="nav-icon">G</span>
              <span>Pengaturan</span>
            </a>
          </nav>
        </aside>

        <main class="app-main">
          <header class="topbar">
            <button id="toggleSidebar" class="icon-button" type="button" aria-label="Buka tutup menu">☰</button>
            <div class="topbar-search">
              <span>⌕</span>
              <input type="search" placeholder="Cari buku, anggota, atau transaksi...">
            </div>
            <div class="topbar-actions">
              <button class="icon-button" type="button" aria-label="Notifikasi">!</button>
              <div class="user-chip">
                <span class="avatar">AD</span>
                <span>Admin</span>
              </div>
            </div>
          </header>

          <div class="content-container">
            @yield('content')
          </div>
        </main>
      </div>
    </div>
  </body>
</html>
