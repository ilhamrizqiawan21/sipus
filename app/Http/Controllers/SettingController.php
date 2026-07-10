<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index(Request $request)
    {
        $settings = $this->settingValues();
        if ($request->wantsJson()) return response()->json($settings);
        return view('settings.index', compact('settings'));
    }

    public function edit(Request $request)
    {
        $settings = $this->settingValues();
        if ($request->wantsJson()) return response()->json($settings);
        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'library_name' => 'nullable|string|max:255',
            'library_address' => 'nullable|string',
            'library_phone' => 'nullable|string|max:20',
            'library_email' => 'nullable|email',
            'max_borrow_limit' => 'nullable|integer|min:1',
            'borrow_duration_days' => 'nullable|integer|min:1',
            'fine_per_day' => 'nullable|numeric|min:0',
        ]);

        foreach ($validated as $key => $value) {
            if ($value !== null) {
                Setting::updateOrCreate(
                    ['key' => $key],
                    ['value' => $value, 'type' => is_numeric($value) ? 'integer' : 'string', 'updated_by' => auth()->id()]
                );
            }
        }

        if ($request->wantsJson()) return response()->json(['message' => 'Updated']);
        return redirect()->route('settings.index')->with('success', 'Pengaturan diperbarui');
    }

    private function settingValues(): array
    {
        return Setting::getAll();
    }
}
