<?php

namespace App\Http\Controllers;

use App\Models\Souscripteur;
use App\Models\Souscription;
use App\Models\Versement;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PdfController extends Controller
{
    /**
     * Autorise l'accès au PDF si :
     *  - l'utilisateur est un administrateur connecté, OU
     *  - la requête porte une signature valide (URL signée générée côté serveur).
     * Empêche l'énumération des PDF par ID (IDOR).
     */
    private function authorizeAccess(Request $request): void
    {
        if ($request->user()?->is_admin) {
            return;
        }

        if (! $request->hasValidSignature()) {
            abort(403, 'Accès non autorisé à ce document.');
        }
    }

    /**
     * Sert le reçu de paiement uploadé par l'admin (image ou PDF).
     * Accès : admin connecté OU URL signée (générée pour l'app/le client).
     */
    public function versementRecu(Request $request, Versement $versement)
    {
        $this->authorizeAccess($request);

        abort_unless($versement->recu, 404, 'Aucun reçu pour ce versement.');
        $full = storage_path('app/public/' . $versement->recu);
        abort_unless(is_file($full), 404, 'Reçu introuvable.');

        return response()->file($full);
    }

    /** Logo embarqué en data-URI base64 (rendu fiable sous DomPDF, sans accès distant). */
    private function logoSrc(): string
    {
        // DomPDF a besoin de l'extension GD pour rasteriser PNG/JPEG.
        // Si GD est absent (certains environnements de dev), on omet le logo
        // et l'entête affiche le libellé texte de la marque — le PDF reste généré.
        if (! \extension_loaded('gd')) {
            return '';
        }

        $path = \App\Models\Setting::logoPath();
        if (! is_file($path)) {
            return '';
        }
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $mime = match ($ext) {
            'jpg', 'jpeg' => 'image/jpeg',
            'webp' => 'image/webp',
            'gif' => 'image/gif',
            default => 'image/png',
        };

        return 'data:' . $mime . ';base64,' . base64_encode((string) file_get_contents($path));
    }

    private function buildPdf(string $html): Dompdf
    {
        $options = new Options();
        $options->set('isRemoteEnabled', false);
        $options->set('defaultFont', 'Helvetica');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf;
    }

    /**
     * Facture / reçu pour un versement donné.
     */
    public function facture(Request $request, Versement $versement): Response
    {
        $this->authorizeAccess($request);

        $versement->load('souscription.souscripteur', 'souscription.programme', 'souscription.lot');
        $souscription = $versement->souscription;
        $souscripteur = $souscription->souscripteur;
        $programme = $souscription->programme;
        $lot = $souscription->lot;
        $totalVerse = $souscription->totalVerse();
        $resteAPayer = $souscription->resteAPayer();

        $logoSrc = $this->logoSrc();

        $html = view('pdf.facture', compact(
            'versement', 'souscription', 'souscripteur', 'programme', 'lot', 'totalVerse', 'resteAPayer', 'logoSrc'
        ))->render();

        $dompdf = $this->buildPdf($html);

        $filename = 'Facture-' . $versement->id . '-' . date('Ymd') . '.pdf';

        return new Response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
        ]);
    }

    /**
     * Attestation de paiement (partiel ou complet).
     */
    public function attestation(Request $request, Souscription $souscription): Response
    {
        $this->authorizeAccess($request);

        $souscription->load('souscripteur', 'programme', 'lot', 'versements');
        $souscripteur = $souscription->souscripteur;
        $programme = $souscription->programme;
        $lot = $souscription->lot;
        $versements = $souscription->versements->sortBy('payment_date');
        $totalVerse = $souscription->totalVerse();
        $resteAPayer = $souscription->resteAPayer();
        $isSolde = $souscription->isSolde();

        $logoSrc = $this->logoSrc();

        $html = view('pdf.attestation', compact(
            'souscription', 'souscripteur', 'programme', 'lot', 'versements', 'totalVerse', 'resteAPayer', 'isSolde', 'logoSrc'
        ))->render();

        $dompdf = $this->buildPdf($html);

        $type = $isSolde ? 'Complete' : 'Partielle';
        $filename = 'Attestation-' . $type . '-' . $souscripteur->uid . '.pdf';

        return new Response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
        ]);
    }

    /**
     * Reçu des frais d'ouverture de dossier (séparés du prix du bien).
     */
    public function fraisRecu(Request $request, Souscripteur $souscripteur): Response
    {
        $this->authorizeAccess($request);

        abort_unless($souscripteur->frais_ouverture_payes, 404, 'Frais d\'ouverture non réglés.');

        $logoSrc = $this->logoSrc();

        $html = view('pdf.frais', compact('souscripteur', 'logoSrc'))->render();
        $dompdf = $this->buildPdf($html);

        $filename = 'Recu-Frais-' . $souscripteur->uid . '.pdf';

        return new Response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
        ]);
    }
}
