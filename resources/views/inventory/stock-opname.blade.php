@extends('layouts.app')

@section('content')
<div class="d-flex flex-column gap-3">
  <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
    <div>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-1">
          <li class="breadcrumb-item"><a href="{{ route('inventory.index') }}">Inventaris</a></li>
          <li class="breadcrumb-item active" aria-current="page">Stock Opname</li>
        </ol>
      </nav>
      <h1 class="h3 mb-1">Stock Opname</h1>
      <p class="text-body-secondary mb-0">Daftar pemeriksaan fisik koleksi perpustakaan.</p>
    </div>
    <a href="{{ route('inventory.index') }}" class="btn btn-outline-secondary">Kembali</a>
  </div>

  <div class="card shadow-sm">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead>
          <tr>
            <th>Kode</th>
            <th>Tanggal</th>
            <th>Periode</th>
            <th>Status</th>
            <th class="text-end">Item</th>
          </tr>
        </thead>
        <tbody>
          @forelse($opnames as $opname)
            <tr>
              <td class="fw-semibold">{{ $opname->opname_code }}</td>
              <td>{{ optional($opname->opname_date)->format('d/m/Y') }}</td>
              <td>{{ optional($opname->start_date)->format('d/m/Y') }} - {{ optional($opname->end_date)->format('d/m/Y') }}</td>
              <td><span class="badge text-bg-secondary">{{ $opname->status }}</span></td>
              <td class="text-end">{{ $opname->details_count ?? $opname->details->count() }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="text-center text-body-secondary py-4">Belum ada data stock opname.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  {{ $opnames->links() }}
</div>
@endsection
