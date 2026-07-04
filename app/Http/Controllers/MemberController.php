<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $members = Member::paginate(15);
        if ($request->wantsJson()) return response()->json($members);
        return view('members.index', ['members' => $members]);
    }

    public function show(Request $request, $id)
    {
        $member = Member::findOrFail($id);
        if ($request->wantsJson()) return response()->json($member);
        return view('members.show', ['member' => $member]);
    }

    public function create()
    {
        return view('members.create');
    }

    public function edit($id)
    {
        $member = Member::findOrFail($id);
        return view('members.edit', compact('member'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:150',
            'member_code' => 'required|string|max:30|unique:members,member_code',
            'nis_nisn' => 'nullable|string|max:50',
            'nip' => 'nullable|string|max:50',
            'gender' => 'nullable|in:M,F',
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
            'nis_nisn' => 'nullable|string|max:50',
            'nip' => 'nullable|string|max:50',
            'gender' => 'nullable|in:M,F',
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
