<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DownloadController extends Controller
{
    /** Chemin du fichier APK servi. */
    private function apkPath(): string
    {
        return public_path('downloads/imlux-app.apk');
    }

    /** Page publique de présentation + téléchargement de l'application. */
    public function page(): View
    {
        return view('public.application', [
            'downloads' => (int) Setting::get('apk_downloads', '0'),
            'available' => is_file($this->apkPath()),
        ]);
    }

    /** Télécharge l'APK et incrémente le compteur. */
    public function apk(): BinaryFileResponse|RedirectResponse
    {
        $path = $this->apkPath();
        if (! is_file($path)) {
            return redirect()->route('application')->with('error', "L'application n'est pas encore disponible au téléchargement.");
        }

        Setting::bump('apk_downloads');

        return response()->download($path, 'IMLUX.apk', [
            'Content-Type' => 'application/vnd.android.package-archive',
        ]);
    }
}
