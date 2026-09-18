<?php

namespace App\Http\Controllers;

use App\Http\Requests\MemberRequest;
use App\Models\Classroom;
use App\Models\Member;
use App\Models\User;
use App\Services\MemberSpreadsheetService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class MemberController extends Controller
{
    public function __construct(private readonly MemberSpreadsheetService $spreadsheet) {}

    public function index(Request $request): Response
    {
        $search = $request->string('search')->trim()->toString();
        $jenis = $request->string('jenis')->toString();
        $items = Member::query()->with(['classroom:id,nama', 'user:id,username'])->when($search, fn ($query) => $query->where(fn ($query) => $query->where('nama', 'like', "%{$search}%")->orWhere('nomor_anggota', 'like', "%{$search}%")->orWhere('nis_nip', 'like', "%{$search}%")))->when(in_array($jenis, ['siswa', 'guru'], true), fn ($query) => $query->where('jenis_anggota', $jenis))->latest('id')->paginate(10)->withQueryString();

        return Inertia::render('Master/Index', ['resource' => 'anggota', 'title' => 'Anggota', 'description' => 'Kelola data siswa, guru, dan akun akses perpustakaan.', 'items' => $items, 'columns' => [['key' => 'nomor_anggota', 'label' => 'Nomor Anggota'], ['key' => 'nama', 'label' => 'Nama'], ['key' => 'jenis_anggota', 'label' => 'Jenis'], ['key' => 'classroom.nama', 'label' => 'Kelas'], ['key' => 'user.username', 'label' => 'Username'], ['key' => 'status', 'label' => 'Status', 'type' => 'status']], 'createUrl' => route('members.create'), 'search' => $search, 'importUrl' => route('members.import'), 'filters' => ['jenis' => $jenis]]);
    }

    public function create(): Response
    {
        return Inertia::render('Master/Form', $this->formProps(null));
    }

    public function store(MemberRequest $request): RedirectResponse
    {
        DB::transaction(fn () => $this->saveMember($request->validated()));

        return redirect()->route('members.index')->with('success', 'Anggota berhasil ditambahkan.');
    }

    public function show(Member $member): Response
    {
        return Inertia::render('Master/Show', ['resource' => 'anggota', 'title' => 'Detail Anggota', 'item' => $member->load(['classroom', 'user'])->loadCount(['loans', 'visits']), 'editUrl' => route('members.edit', $member), 'backUrl' => route('members.index')]);
    }

    public function edit(Member $member): Response
    {
        return Inertia::render('Master/Form', $this->formProps($member));
    }

    public function update(MemberRequest $request, Member $member): RedirectResponse
    {
        DB::transaction(fn () => $this->saveMember($request->validated(), $member));

        return redirect()->route('members.index')->with('success', 'Data anggota berhasil diperbarui.');
    }

    public function destroy(Member $member): RedirectResponse
    {
        if ($member->loans()->exists() || $member->visits()->exists()) {
            return back()->with('error', 'Anggota tidak dapat dihapus karena memiliki histori transaksi.');
        }
        DB::transaction(function () use ($member): void {
            $user = $member->user;
            $member->delete();
            if ($user !== null) {
                $user->delete();
            }
        });

        return back()->with('success', 'Anggota berhasil dihapus.');
    }

    public function import(): Response
    {
        return Inertia::render('Members/Import', ['templateUrl' => route('members.import.template'), 'backUrl' => route('members.index')]);
    }

    public function preview(Request $request): Response
    {
        $request->validate(['file' => ['required', 'file', 'mimes:xlsx', 'max:5120']]);
        $rows = $this->spreadsheet->validateRows($this->spreadsheet->parse($request->file('file')));
        $token = (string) Str::uuid();
        Storage::disk('local')->put("member-imports/{$token}.json", json_encode($rows, JSON_THROW_ON_ERROR));

        return Inertia::render('Members/ImportPreview', ['token' => $token, 'rows' => $rows, 'backUrl' => route('members.import')]);
    }

    public function confirmImport(Request $request): RedirectResponse
    {
        $data = $request->validate(['token' => ['required', 'uuid']]);
        $path = "member-imports/{$data['token']}.json";
        abort_unless(Storage::disk('local')->exists($path), 404, 'Preview import sudah kedaluwarsa.');
        $rows = json_decode(Storage::disk('local')->get($path), true, 512, JSON_THROW_ON_ERROR);
        $validRows = collect($this->spreadsheet->validateRows($rows))->where('_valid', true)->values();
        DB::transaction(function () use ($validRows): void {
            $validRows->each(fn (array $row) => $this->saveImportedMember($row));
        });
        Storage::disk('local')->delete($path);

        return redirect()->route('members.index')->with('success', "{$validRows->count()} anggota berhasil diimport.");
    }

    public function template(): BinaryFileResponse
    {
        return response()->download($this->spreadsheet->templatePath(), 'template-anggota.xlsx')->deleteFileAfterSend(true);
    }

    private function formProps(?Member $member): array
    {
        $item = $member?->load('user')->toArray() ?? ['tanggal_daftar' => now()->toDateString(), 'status' => true];
        $item['username'] = $member?->user?->username;

        return ['resource' => 'anggota', 'title' => $member ? 'Edit Anggota' : 'Tambah Anggota', 'item' => $member ? $item : null, 'fields' => [['name' => 'nomor_anggota', 'label' => 'Nomor anggota', 'required' => true], ['name' => 'jenis_anggota', 'label' => 'Jenis anggota', 'type' => 'select', 'required' => true, 'options' => [['value' => 'siswa', 'label' => 'Siswa'], ['value' => 'guru', 'label' => 'Guru']]], ['name' => 'nama', 'label' => 'Nama lengkap', 'required' => true], ['name' => 'nis_nip', 'label' => 'NIS/NIP'], ['name' => 'kelas_id', 'label' => 'Kelas', 'type' => 'select', 'options' => Classroom::query()->where('aktif', true)->orderBy('nama')->get(['id', 'nama'])->map(fn ($classroom) => ['value' => $classroom->id, 'label' => $classroom->nama])->values()], ['name' => 'jenis_kelamin', 'label' => 'Jenis kelamin'], ['name' => 'tempat_lahir', 'label' => 'Tempat lahir'], ['name' => 'tanggal_lahir', 'label' => 'Tanggal lahir', 'type' => 'date'], ['name' => 'alamat', 'label' => 'Alamat', 'type' => 'textarea'], ['name' => 'telepon', 'label' => 'Telepon'], ['name' => 'email', 'label' => 'Email', 'type' => 'email'], ['name' => 'tanggal_daftar', 'label' => 'Tanggal daftar', 'type' => 'date', 'required' => true], ['name' => 'username', 'label' => 'Username login'], ['name' => 'password', 'label' => 'Password login', 'type' => 'password'], ['name' => 'password_confirmation', 'label' => 'Konfirmasi password', 'type' => 'password'], ['name' => 'status', 'label' => 'Anggota aktif', 'type' => 'checkbox']], 'action' => $member ? route('members.update', $member) : route('members.store'), 'method' => $member ? 'patch' : 'post', 'backUrl' => route('members.index')];
    }

    private function saveMember(array $data, ?Member $member = null): Member
    {
        $account = Arr::only($data, ['username', 'password']);
        $memberData = Arr::except($data, ['username', 'password', 'password_confirmation']);
        $memberData['status'] ??= true;
        $member ??= new Member;
        $member->fill($memberData);
        if ($account['username'] ?? null) {
            $user = $member->user ?? new User;
            $user->fill(['nama' => $memberData['nama'], 'username' => $account['username'], 'role' => $memberData['jenis_anggota'], 'status' => $memberData['status']]);
            if ($account['password'] ?? null) {
                $user->password = $account['password'];
            }
            $user->save();
            $member->user()->associate($user);
        }
        $member->save();

        return $member;
    }

    private function saveImportedMember(array $row): void
    {
        $classroomId = ! empty($row['kelas']) ? Classroom::where('nama', $row['kelas'])->value('id') : null;
        $this->saveMember(['nomor_anggota' => $row['nomor_anggota'], 'jenis_anggota' => $row['jenis_anggota'], 'nama' => $row['nama'], 'nis_nip' => $row['nis_nip'] ?? null, 'kelas_id' => $classroomId, 'jenis_kelamin' => $row['jenis_kelamin'] ?? null, 'tempat_lahir' => $row['tempat_lahir'] ?? null, 'tanggal_lahir' => $row['tanggal_lahir'] ?? null, 'alamat' => $row['alamat'] ?? null, 'telepon' => $row['telepon'] ?? null, 'email' => $row['email'] ?? null, 'tanggal_daftar' => $row['tanggal_daftar'] ?? now()->toDateString(), 'status' => true, 'username' => $row['username'] ?? null, 'password' => $row['password'] ?? null]);
    }
}
