@extends('layouts.app')

@section('content')
  <div class="d-flex flex-column gap-3">
    <section class="d-flex flex-wrap align-items-center justify-content-between gap-3">
      <div>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-1">
            <li class="breadcrumb-item"><a href="{{ route('inventory.index') }}">Inventaris</a></li>
            <li class="breadcrumb-item active">Eksemplar</li>
          </ol>
        </nav>
        <h1 class="h3 mb-1">Daftar Eksemplar</h1>
        <p class="text-body-secondary mb-0">Kelola salinan fisik buku.</p>
      </div>
      <a class="btn btn-primary" href="{{ route('copies.create') }}">Tambah Eksemplar</a>
    </section>

    @if(session('success'))
      <div class="alert alert-success mb-0">{{ session('success') }}</div>
    @endif
    @if($errors->any())
      <div class="alert alert-danger mb-0">{{ $errors->first() }}</div>
    @endif

    <section class="card shadow-sm">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th>Kode Inventaris</th>
              <th>Barcode</th>
              <th>Buku</th>
              <th>Rak</th>
              <th>Status</th>
              <th>Kondisi</th>
              <th class="text-end">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($copies as $c)
              <tr>
                <td class="font-monospace small fw-semibold">{{ $c->inventory_code }}</td>
                <td class="font-monospace small">{{ $c->barcode ?? '-' }}</td>
                <td>
                  @if($c->book)
                    {{ $c->book->title }}
                  @else
                    <span class="badge text-bg-warning">Buku terhapus</span>
                  @endif
                </td>
                <td>{{ $c->bookshelf ? $c->bookshelf->code . ' - ' . $c->bookshelf->name : '-' }}</td>
                @php($statusCode = optional($c->status)->code)
                @php($statusBadge = match ($statusCode) {
                  'available' => 'success',
                  'borrowed' => 'warning',
                  'damaged', 'lost' => 'danger',
                  default => 'secondary',
                })
                <td><span class="badge text-bg-{{ $statusBadge }}">{{ optional($c->status)->name ?? '-' }}</span></td>
                <td>{{ optional($c->condition)->name ?? '-' }}</td>
                <td class="text-end">
                  <div class="btn-group btn-group-sm">
                    <a class="btn btn-outline-secondary" href="{{ route('copies.edit', $c->id) }}">Edit</a>
                    <form action="{{ route('copies.destroy', $c->id) }}" method="post" data-confirm="Hapus eksemplar ini?">
                      @csrf @method('delete')
                      <button class="btn btn-outline-danger rounded-start-0" type="submit">Hapus</button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr><td colspan="7" class="text-center text-body-secondary py-5">Belum ada eksemplar.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div class="card-footer d-flex justify-content-end">{{ $copies->links() }}</div>
    </section>
  </div>
@endsection
