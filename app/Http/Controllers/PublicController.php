<?php

namespace App\Http\Controllers;

use App\Models\Bien;
use App\Models\Programme;
use Illuminate\View\View;

class PublicController extends Controller
{
    private function programmesQuery()
    {
        return Programme::where('status', 'actif')
            ->withCount(['lots', 'lots as lots_disponibles_count' => fn ($q) => $q->where('status', 'disponible')])
            ->orderBy('name');
    }

    public function home(): View
    {
        $programmes = $this->programmesQuery()->take(6)->get();
        $biens = Bien::orderBy('ordre')->latest()->take(3)->get();

        return view('public.home', compact('programmes', 'biens'));
    }

    public function biens(\Illuminate\Http\Request $request): View
    {
        $biens = Bien::query()
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = '%'.$request->string('q').'%';
                $query->where(fn ($w) => $w->where('name', 'like', $term)
                    ->orWhere('type', 'like', $term)
                    ->orWhere('description', 'like', $term));
            })
            ->when($request->filled('type'), fn ($query) => $query->where('type', $request->string('type')))
            ->when($request->filled('budget') && is_numeric($request->input('budget')),
                fn ($query) => $query->where('price', '<=', (float) $request->input('budget')))
            ->orderByRaw("FIELD(status,'disponible','reserve','vendu')")
            ->orderBy('ordre')->latest()->get();

        return view('public.biens', compact('biens'));
    }

    public function presentation(): View
    {
        $biens = Bien::orderBy('ordre')->latest()->take(2)->get();
        $programmesCount = Programme::where('status', 'actif')->count();

        return view('public.presentation', compact('biens', 'programmesCount'));
    }

    public function programmes(): View
    {
        $programmes = $this->programmesQuery()->get();

        return view('public.programmes', compact('programmes'));
    }

    public function adhesion(): View
    {
        return view('public.adhesion');
    }

    public function faq(): View
    {
        $faqs = self::faqs();

        return view('public.faq', compact('faqs'));
    }

    public function contact(): View
    {
        return view('public.contact');
    }

    public static function faqs(): array
    {
        return [
            [
                'q' => "Qu'est-ce qu'une adhésion chez Lorny Conseils Management ?",
                'a' => "L'adhésion est votre engagement à acquérir un lot dans l'un de nos programmes. Vous versez un apport initial, puis réglez le solde selon un échéancier personnalisé (mensuel, trimestriel ou annuel), jusqu'à la remise de votre titre de propriété.",
            ],
            [
                'q' => "Quel est l'apport minimum pour adhérer ?",
                'a' => "Un apport initial de 35 % du montant du bien est requis. Les échéances ne démarrent qu'une fois ce seuil atteint — ce qui sécurise votre engagement et le nôtre.",
            ],
            [
                'q' => "Comment se passe la création de mon compte ?",
                'a' => "Vous créez votre compte en ligne en quelques minutes. Un conseiller du cabinet vérifie ensuite votre dossier et valide votre accès. Vous êtes notifié dès l'activation de votre espace membre.",
            ],
            [
                'q' => "Les frais d'ouverture de dossier sont-ils inclus ?",
                'a' => "Non. Les frais d'ouverture de dossier (500 000 FCFA) sont distincts du prix du bien et de l'échéancier. Un reçu vous est délivré dès leur règlement.",
            ],
            [
                'q' => "Puis-je suivre l'avancement des travaux ?",
                'a' => "Oui. Depuis votre espace membre et l'application mobile, vous suivez en temps réel l'avancement du chantier par niveau, avec photos, et recevez une notification à chaque étape franchie.",
            ],
            [
                'q' => "Quels documents puis-je obtenir ?",
                'a' => "À chaque versement, une facture est générée ; une attestation de paiement (partielle ou complète) et le reçu des frais de dossier sont disponibles à tout moment depuis votre espace.",
            ],
        ];
    }
}
