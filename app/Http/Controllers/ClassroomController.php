<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClassroomRequest;
use App\Models\Classroom;
use App\Models\SchoolYear;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ClassroomController extends Controller
{
    public function index(Request $request): Response
    {
        $search = $request->string('search')->trim()->toString();
        $items = Classroom::query()->with('schoolYear:id,nama,semester')->when($search, fn ($query) => $query->where('nama', 'like', "%{$search}%"))->latest('id')->paginate(10)->withQueryString();

        return Inertia::render('Master/Index', ['resource' => 'classrooms', 'title' => 'Kelas', 'description' => 'Kelola daftar kelas dan wali kelas.', 'items' => $items, 'columns' => [['key' => 'nama', 'label' => 'Nama Kelas'], ['key' => 'tingkat', 'label' => 'Tingkat'], ['key' => 'school_year.nama', 'label' => 'Tahun Ajaran'], ['key' => 'wali_nama', 'label' => 'Wali Kelas'], ['key' => 'aktif', 'label' => 'Status', 'type' => 'status']], 'createUrl' => route('classrooms.create'), 'search' => $search]);
    }

    public function create(): Response
    {
        return Inertia::render('Master/Form', $this->formProps(null));
    }

    public function store(ClassroomRequest $request): RedirectResponse
    {
        Classroom::create($request->validated());

        return redirect()->route('classrooms.index')->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function show(Classroom $classroom): Response
    {
        return Inertia::render('Master/Show', ['resource' => 'classrooms', 'title' => 'Detail Kelas', 'item' => $classroom->load(['schoolYear', 'members'])->loadCount('members'), 'editUrl' => route('classrooms.edit', $classroom), 'backUrl' => route('classrooms.index')]);
    }

    public function edit(Classroom $classroom): Response
    {
        return Inertia::render('Master/Form', $this->formProps($classroom));
    }

    public function update(ClassroomRequest $request, Classroom $classroom): RedirectResponse
    {
        $classroom->update($request->validated());

        return redirect()->route('classrooms.index')->with('success', 'Kelas berhasil diperbarui.');
    }

    public function destroy(Classroom $classroom): RedirectResponse
    {
        if ($classroom->members()->exists()) {
            return back()->with('error', 'Kelas tidak dapat dihapus karena masih memiliki anggota.');
        }
        $classroom->delete();

        return back()->with('success', 'Kelas berhasil dihapus.');
    }

    private function formProps(?Classroom $classroom): array
    {
        return ['resource' => 'classrooms', 'title' => $classroom ? 'Edit Kelas' : 'Tambah Kelas', 'item' => $classroom, 'fields' => [['name' => 'nama', 'label' => 'Nama kelas', 'required' => true], ['name' => 'tingkat', 'label' => 'Tingkat', 'required' => true], ['name' => 'school_year_id', 'label' => 'Tahun ajaran', 'type' => 'select', 'required' => true, 'options' => SchoolYear::query()->orderByDesc('mulai')->get(['id', 'nama', 'semester'])->map(fn ($year) => ['value' => $year->id, 'label' => "{$year->nama} — Semester {$year->semester}"])->values()], ['name' => 'wali_nama', 'label' => 'Nama wali kelas'], ['name' => 'aktif', 'label' => 'Kelas aktif', 'type' => 'checkbox', 'default' => true]], 'action' => $classroom ? route('classrooms.update', $classroom) : route('classrooms.store'), 'method' => $classroom ? 'patch' : 'post', 'backUrl' => route('classrooms.index')];
    }
}
