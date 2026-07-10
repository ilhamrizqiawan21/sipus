<!doctype html>
<html lang="id">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>{{ config('app.name', 'SIPUS') }}</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @if (class_exists('Illuminate\Support\Facades\Vite'))
      @vite(['resources/css/app.css','resources/js/app.js'])
    @else
      <link rel="stylesheet" href="/resources/css/app.css">
      <script src="/resources/js/app.js" defer></script>
    @endif
  </head>
  <body class="bg-body-tertiary">
    <a class="skip-link" href="#main-content">Lewati ke konten</a>
    <div class="app-shell">
      <div class="app-body">
        <aside class="app-sidebar" id="sidebar">
          <div class="sidebar-brand">
            <img class="brand-logo" src="{{ asset('logo-sekolah.png') }}" alt="Logo MTs Al-Ihsan">
            <div>
              <div class="brand-title">SIPUS</div>
              <div class="brand-subtitle">MTs Al-Ihsan</div>
            </div>
          </div>

          <nav class="sidebar-nav" aria-label="Navigasi utama">
            <div class="nav-section">Menu</div>
            <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
              <span class="nav-icon">DB</span>
              <span>Dashboard</span>
            </a>

            <div class="nav-section">Koleksi</div>
            <a class="nav-link {{ request()->routeIs('books.*') ? 'active' : '' }}" href="{{ route('books.index') }}">
              <span class="nav-icon">KB</span>
              <span>Katalog Buku</span>
            </a>
            <a class="nav-link {{ request()->routeIs('inventory.index') || request()->routeIs('copies.*') ? 'active' : '' }}" href="{{ route('inventory.index') }}">
              <span class="nav-icon">EK</span>
              <span>Eksemplar</span>
            </a>
            <a class="nav-link {{ request()->routeIs('procurements.*') ? 'active' : '' }}" href="{{ route('procurements.index') }}">
              <span class="nav-icon">P</span>
              <span>Pengadaan</span>
            </a>

            <div class="nav-section">Sirkulasi</div>
            <a class="nav-link {{ request()->routeIs('members.*') ? 'active' : '' }}" href="{{ route('members.index') }}">
              <span class="nav-icon">AG</span>
              <span>Anggota</span>
            </a>
            <a class="nav-link {{ request()->routeIs('loans.index') || request()->routeIs('loans.borrow') || request()->routeIs('loans.show') ? 'active' : '' }}" href="{{ route('loans.index') }}">
              <span class="nav-icon">PJ</span>
              <span>Peminjaman</span>
            </a>
            <a class="nav-link {{ request()->routeIs('loans.return') ? 'active' : '' }}" href="{{ route('loans.return') }}">
              <span class="nav-icon">KB</span>
              <span>Pengembalian</span>
            </a>

            <div class="nav-section">Sistem</div>
            <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" href="{{ route('reports.index') }}">
              <span class="nav-icon">LP</span>
              <span>Laporan</span>
            </a>
            <a class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}" href="{{ route('settings.index') }}">
              <span class="nav-icon">PG</span>
              <span>Pengaturan</span>
            </a>
          </nav>
        </aside>
        <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

        <main class="app-main">
          <header class="topbar">
            <button id="toggleSidebar" class="icon-button" type="button" aria-label="Buka tutup menu">
              <span class="menu-lines" aria-hidden="true"></span>
            </button>
            <div class="topbar-search">
              <span class="search-mark" aria-hidden="true"></span>
              <input type="search" placeholder="Cari buku, anggota, atau transaksi...">
            </div>
            <div class="topbar-actions">
              @auth
                <div class="user-chip">
                  <span class="avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</span>
                  <span>{{ auth()->user()->name }}</span>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                  @csrf
                  <button class="btn btn-outline-secondary btn-sm" type="submit">Logout</button>
                </form>
              @else
                <a class="btn btn-primary btn-sm" href="{{ route('login') }}">Login</a>
              @endauth
            </div>
          </header>

          <div class="content-container" id="main-content">
            @if(session('error'))
              <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
              </div>
            @endif
            @if(session('status'))
              <div class="alert alert-info alert-dismissible fade show" role="alert">
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
              </div>
            @endif
            @yield('content')
          </div>
        </main>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
