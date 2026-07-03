<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;

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

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:150',
            'member_code' => 'required|string|unique:members,member_code',
        ]);

        $member = Member::create($data);
        if ($request->wantsJson()) return response()->json($member, 201);
        return redirect()->route('members.show', $member->id);
    }

    public function update(Request $request, $id)
    {
        $member = Member::findOrFail($id);
        $member->update($request->only(['name','member_code']));
        if ($request->wantsJson()) return response()->json($member);
        return redirect()->route('members.show', $member->id);
    }

    public function destroy(Request $request, $id)
    {
        $member = Member::findOrFail($id);
        $member->delete();
        if ($request->wantsJson()) return response()->json(['message' => 'Deleted']);
        return redirect()->route('members.index');
    }
}
