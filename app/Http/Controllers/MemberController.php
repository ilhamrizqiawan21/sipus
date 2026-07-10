<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Classes;
use App\Models\MemberType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $query = Member::with(['memberType', 'class']);

        if ($search = trim($request->query('q', ''))) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('member_code', 'like', "%{$search}%")
                    ->orWhere('nis_nisn', 'like', "%{$search}%")
                    ->orWhere('nip', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $members = $query->paginate(15)->withQueryString();

        if ($request->wantsJson()) return response()->json($members);
        return view('members.index', ['members' => $members]);
    }

    public function show(Request $request, $id)
    {
        $member = Member::with(['memberType', 'class'])->findOrFail($id);
        if ($request->wantsJson()) return response()->json($member);
        return view('members.show', ['member' => $member]);
    }

    public function create()
    {
        return view('members.create', [
            'memberTypes' => MemberType::orderBy('name')->get(),
            'classes' => Classes::orderBy('name')->get(),
        ]);
    }

    public function edit($id)
    {
        $member = Member::findOrFail($id);
        return view('members.edit', [
            'member' => $member,
            'memberTypes' => MemberType::orderBy('name')->get(),
            'classes' => Classes::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:150',
            'member_code' => 'required|string|max:30|unique:members,member_code',
            'member_type_id' => 'nullable|integer|exists:member_types,id',
            'class_id' => 'nullable|integer|exists:classes,id',
            'nis_nisn' => 'nullable|string|max:50',
            'nip' => 'nullable|string|max:50',
            'gender' => 'nullable|in:L,P',
            'birth_date' => 'nullable|date',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'card_number' => 'nullable|string|max:50',
            'join_date' => 'nullable|date',
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active', true);
        $member = Member::create($data);
        if ($request->wantsJson()) return response()->json($member, 201);
        return redirect()->route('members.show', $member->id)->with('success', 'Anggota berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $member = Member::findOrFail($id);
        $data = $request->validate([
            'name' => 'required|string|max:150',
            'member_code' => ['required', 'string', 'max:30', Rule::unique('members', 'member_code')->ignore($member->id)],
            'member_type_id' => 'nullable|integer|exists:member_types,id',
            'class_id' => 'nullable|integer|exists:classes,id',
            'nis_nisn' => 'nullable|string|max:50',
            'nip' => 'nullable|string|max:50',
            'gender' => 'nullable|in:L,P',
            'birth_date' => 'nullable|date',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'card_number' => 'nullable|string|max:50',
            'join_date' => 'nullable|date',
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $member->update($data);
        if ($request->wantsJson()) return response()->json($member);
        return redirect()->route('members.show', $member->id)->with('success', 'Data anggota diperbarui.');
    }

    public function destroy(Request $request, $id)
    {
        $member = Member::findOrFail($id);
        $member->delete();
        if ($request->wantsJson()) return response()->json(['message' => 'Deleted']);
        return redirect()->route('members.index')->with('success', 'Anggota dihapus.');
    }
}
