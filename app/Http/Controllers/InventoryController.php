<?php

namespace App\Http\Controllers;

use App\Http\Requests\InventoryRequest;
use App\Models\Inventory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class InventoryController extends Controller
{
    public function index(Request $request): Response
    {
        $search = $request->string('search')->trim()->toString();
        $items = Inventory::query()->when($search, fn ($query) => $query->where('nama_barang', 'like', "%{$search}%")->orWhere('kode_inventaris', 'like', "%{$search}%"))->latest('id')->paginate(10)->withQueryString();

        return Inertia::render('Master/Index', ['resource' => 'inventaris', 'title' => 'Inventaris Non-Buku', 'description' => 'Kelola aset dan perlengkapan perpustakaan selain buku.', 'items' => $items, 'columns' => [['key' => 'kode_inventaris', 'label' => 'Kode'], ['key' => 'nama_barang', 'label' => 'Nama Barang'], ['key' => 'jenis', 'label' => 'Jenis'], ['key' => 'jumlah', 'label' => 'Jumlah'], ['key' => 'lokasi', 'label' => 'Lokasi'], ['key' => 'kondisi', 'label' => 'Kondisi'], ['key' => 'status', 'label' => 'Status']], 'createUrl' => route('inventories.create'), 'search' => $search]);
    }

    public function create(): Response
    {
        return Inertia::render('Master/Form', $this->formProps(null));
    }

    public function store(InventoryRequest $request): RedirectResponse
    {
        Inventory::create($request->validated());

        return redirect()->route('inventories.index')->with('success', 'Inventaris berhasil ditambahkan.');
    }

    public function show(Inventory $inventory): Response
    {
        return Inertia::render('Master/Show', ['title' => 'Detail Inventaris', 'item' => $inventory, 'editUrl' => route('inventories.edit', $inventory), 'backUrl' => route('inventories.index')]);
    }

    public function edit(Inventory $inventory): Response
    {
        return Inertia::render('Master/Form', $this->formProps($inventory));
    }

    public function update(InventoryRequest $request, Inventory $inventory): RedirectResponse
    {
        $inventory->update($request->validated());

        return redirect()->route('inventories.index')->with('success', 'Inventaris berhasil diperbarui.');
    }

    public function destroy(Inventory $inventory): RedirectResponse
    {
        $inventory->delete();

        return back()->with('success', 'Inventaris berhasil dihapus.');
    }

    private function formProps(?Inventory $inventory): array
    {
        return ['title' => $inventory ? 'Edit Inventaris' : 'Tambah Inventaris', 'item' => $inventory, 'fields' => [['name' => 'kode_inventaris', 'label' => 'Kode inventaris', 'required' => true], ['name' => 'nama_barang', 'label' => 'Nama barang', 'required' => true], ['name' => 'jenis', 'label' => 'Jenis', 'required' => true], ['name' => 'jumlah', 'label' => 'Jumlah', 'type' => 'number', 'required' => true, 'default' => 1], ['name' => 'satuan', 'label' => 'Satuan', 'required' => true, 'default' => 'unit'], ['name' => 'lokasi', 'label' => 'Lokasi'], ['name' => 'kondisi', 'label' => 'Kondisi', 'type' => 'select', 'required' => true, 'default' => 'baik', 'options' => [['value' => 'baik', 'label' => 'Baik'], ['value' => 'rusak_ringan', 'label' => 'Rusak ringan'], ['value' => 'rusak_berat', 'label' => 'Rusak berat'], ['value' => 'hilang', 'label' => 'Hilang']]], ['name' => 'status', 'label' => 'Status', 'type' => 'select', 'required' => true, 'default' => 'aktif', 'options' => [['value' => 'aktif', 'label' => 'Aktif'], ['value' => 'diperbaiki', 'label' => 'Diperbaiki'], ['value' => 'dihapus', 'label' => 'Dihapus']]], ['name' => 'tanggal_perolehan', 'label' => 'Tanggal perolehan', 'type' => 'date'], ['name' => 'harga_perolehan', 'label' => 'Harga perolehan', 'type' => 'number'], ['name' => 'catatan', 'label' => 'Catatan', 'type' => 'textarea']], 'action' => $inventory ? route('inventories.update', $inventory) : route('inventories.store'), 'method' => $inventory ? 'patch' : 'post', 'backUrl' => route('inventories.index')];
    }
}
