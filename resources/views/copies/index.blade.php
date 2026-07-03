@extends('layouts.app')

@section('content')
  <div class="page-stack">
    <section class="page-header">
      <div>
        <div class="breadcrumb"><a href="{{ route('inventory.index') }}">Inventory</a> / Eksamplar</div>
        <h1>Daftar Eksamplar</h1>
        <p>Kelola salinan fisik buku.</p>
      </div>
      <a class="btn btn-primary" href="{{ route('copies.create') }}">Tambah Eksamplar</a>
    </section>

    <section class="panel">
      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif
      <table class="table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Barcode</th>
            <th>Buku</th>
            <th>Lokasi</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach($copies as $c)
            <tr>
              <td>{{ $c->id }}</td>
              <td>{{ $c->barcode }}</td>
              <td>{{ optional($c->book)->title }}</td>
              <td>{{ $c->location }}</td>
              <td>{{ $c->status }}</td>
              <td>
                <a class="btn btn-sm" href="{{ route('copies.edit', $c->id) }}">Edit</a>
                <form action="{{ route('copies.destroy', $c->id) }}" method="post" style="display:inline">
                  @csrf @method('delete')
                  <button class="btn btn-sm btn-danger" type="submit" onclick="return confirm('Hapus?')">Hapus</button>
                </form>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
      {{ $copies->links() }}
    </section>
  </div>
@endsection
