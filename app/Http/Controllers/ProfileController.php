<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\Models\ActivityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'user' => $request->user()->load('member'),
        ]);
    }

    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validated();
        $password = $validated['password'] ?? null;
        unset($validated['password'], $validated['password_confirmation'], $validated['foto']);

        if ($password !== null && $password !== '') {
            $validated['password'] = $password;
        }

        $user->update($validated);

        if ($request->hasFile('foto') && $user->member !== null) {
            $path = $request->file('foto')->store('member-photos', 'public');
            $oldPath = $user->member->foto_path;
            $user->member->update(['foto_path' => $path]);

            if ($oldPath !== null) {
                Storage::disk('public')->delete($oldPath);
            }
        }

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'profile_updated',
            'description' => 'Profil pengguna diperbarui.',
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
