<?php

namespace App\Http\Controllers;

use App\Http\Requests\SchoolYearRequest;
use App\Models\SchoolYear;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SchoolYearController extends Controller
{
    public function index(Request $request): Response
    {
        $search = $request->string('search')->trim()->toString();
        $items = SchoolYear::query()->when($search, fn ($query) => $query->where('nama', 'like', "%{$search}%"))->orderByDesc('mulai')->paginate(10)->withQueryString();

        return Inertia::render('Master/Index', ['resource' => 'school-years', 'title' => 'Tahun Ajaran', 'description' => 'Kelola periode tahun ajaran dan semester aktif.', 'items' => $items, 'columns' => [['key' => 'nama', 'label' => 'Tahun Ajaran'], ['key' => 'semester', 'label' => 'Semester'], ['key' => 'mulai', 'label' => 'Mulai'], ['key' => 'selesai', 'label' => 'Selesai'], ['key' => 'is_aktif', 'label' => 'Status', 'type' => 'status']], 'createUrl' => route('school-years.create'), 'search' => $search]);
    }

    public function create(): Response
    {
        return Inertia::render('Master/Form', $this->formProps(null));
    }

    public function store(SchoolYearRequest $request): RedirectResponse
    {
        SchoolYear::create($request->validated());

        return redirect()->route('school-years.index')->with('success', 'Tahun ajaran berhasil ditambahkan.');
    }

    public function show(SchoolYear $schoolYear): Response
    {
        return Inertia::render('Master/Show', ['resource' => 'school-years', 'title' => 'Detail Tahun Ajaran', 'item' => $schoolYear->loadCount('classes'), 'editUrl' => route('school-years.edit', $schoolYear), 'backUrl' => route('school-years.index')]);
    }

    public function edit(SchoolYear $schoolYear): Response
    {
        return Inertia::render('Master/Form', $this->formProps($schoolYear));
    }

    public function update(SchoolYearRequest $request, SchoolYear $schoolYear): RedirectResponse
    {
        $schoolYear->update($request->validated());

        return redirect()->route('school-years.index')->with('success', 'Tahun ajaran berhasil diperbarui.');
    }

    public function destroy(SchoolYear $schoolYear): RedirectResponse
    {
        if ($schoolYear->classes()->exists()) {
            return back()->with('error', 'Tahun ajaran tidak dapat dihapus karena masih digunakan oleh kelas.');
        }
        $schoolYear->delete();

        return back()->with('success', 'Tahun ajaran berhasil dihapus.');
    }

    private function formProps(?SchoolYear $schoolYear): array
    {
        return ['resource' => 'school-years', 'title' => $schoolYear ? 'Edit Tahun Ajaran' : 'Tambah Tahun Ajaran', 'item' => $schoolYear, 'fields' => [['name' => 'nama', 'label' => 'Tahun ajaran', 'required' => true], ['name' => 'semester', 'label' => 'Semester', 'type' => 'select', 'required' => true, 'options' => [['value' => '1', 'label' => 'Semester 1'], ['value' => '2', 'label' => 'Semester 2']]], ['name' => 'mulai', 'label' => 'Tanggal mulai', 'type' => 'date', 'required' => true], ['name' => 'selesai', 'label' => 'Tanggal selesai', 'type' => 'date', 'required' => true], ['name' => 'is_aktif', 'label' => 'Tandai sebagai aktif', 'type' => 'checkbox']], 'action' => $schoolYear ? route('school-years.update', $schoolYear) : route('school-years.store'), 'method' => $schoolYear ? 'patch' : 'post', 'backUrl' => route('school-years.index')];
    }
}
