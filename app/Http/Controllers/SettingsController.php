<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'company_name' => ['required', 'string', 'max:150'],
            'company_tagline' => ['nullable', 'string', 'max:150'],
            'company_address' => ['nullable', 'string', 'max:300'],
            'company_phone' => ['nullable', 'string', 'max:80'],
            'company_email' => ['nullable', 'string', 'max:150'],
            'company_rccm' => ['nullable', 'string', 'max:100'],
            'company_ncc' => ['nullable', 'string', 'max:100'],
            'company_website' => ['nullable', 'string', 'max:150'],
            'company_footer' => ['nullable', 'string', 'max:400'],
            'logo' => ['nullable', 'image', 'max:4096'],
        ]);

        foreach (array_keys(Setting::DEFAULTS) as $key) {
            if ($key === 'company_logo') {
                continue;
            }
            if (array_key_exists($key, $validated)) {
                Setting::put($key, $validated[$key]);
            }
        }

        // Logo personnalisé
        if ($request->hasFile('logo')) {
            $old = Setting::get('company_logo');
            $path = $request->file('logo')->store('company', 'public');
            Setting::put('company_logo', $path);
            if ($old && Storage::disk('public')->exists($old)) {
                Storage::disk('public')->delete($old);
            }
        }

        return redirect(route('admin.dashboard') . '#parametres')->with('success', 'Paramètres enregistrés.');
    }

    public function resetLogo(): RedirectResponse
    {
        $old = Setting::get('company_logo');
        if ($old && Storage::disk('public')->exists($old)) {
            Storage::disk('public')->delete($old);
        }
        Setting::put('company_logo', null);

        return redirect(route('admin.dashboard') . '#parametres')->with('success', 'Logo réinitialisé (logo par défaut).');
    }
}
