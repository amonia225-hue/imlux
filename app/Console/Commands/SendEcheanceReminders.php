<?php

namespace App\Console\Commands;

use App\Models\ClientNotification;
use App\Models\Souscription;
use App\Services\ClientNotifier;
use Illuminate\Console\Command;

class SendEcheanceReminders extends Command
{
    protected $signature = 'notifications:rappels {--jours=5 : Fenêtre de rappel avant échéance (jours)}';

    protected $description = 'Envoie aux clients les rappels d\'échéances à venir ou en retard';

    public function handle(ClientNotifier $notifier): int
    {
        $seuil = (int) $this->option('jours');
        $envoyes = 0;

        $souscriptions = Souscription::where('status', 'en_cours')
            ->with(['souscripteur', 'programme', 'lot', 'versements'])
            ->get();

        foreach ($souscriptions as $s) {
            $sc = $s->souscripteur;
            if (! $sc) {
                continue;
            }

            $prochaine = $s->prochaineEcheance();
            if (! $prochaine) {
                continue;
            }

            $joursRestants = (int) round(now()->startOfDay()->diffInDays($prochaine['date']->copy()->startOfDay(), false));

            // On ne notifie que dans la fenêtre (à venir <= seuil jours, ou en retard)
            if ($joursRestants > $seuil) {
                continue;
            }

            // Dé-doublonnage : une seule notification par échéance
            $dejaPrevenu = ClientNotification::where('souscripteur_id', $sc->id)
                ->where('type', 'echeance')
                ->whereJsonContains('data->souscription_id', $s->id)
                ->whereJsonContains('data->echeance_n', $prochaine['n'])
                ->exists();

            if ($dejaPrevenu) {
                continue;
            }

            $montant = number_format($s->echeanceActuelle(), 0, ',', ' ');
            $date = $prochaine['date']->format('d/m/Y');

            $body = $joursRestants < 0
                ? "Échéance en retard : {$montant} FCFA était attendue le {$date} pour votre lot {$s->lot->reference}."
                : "Rappel : votre prochaine échéance de {$montant} FCFA est prévue le {$date} ({$s->rythmeLabel()}).";

            $notifier->notify($sc, 'echeance', 'Rappel d\'échéance', $body, [
                'souscription_id' => $s->id,
                'echeance_n' => $prochaine['n'],
            ]);
            $envoyes++;
        }

        $this->info("Rappels d'échéances envoyés : {$envoyes}");
        return self::SUCCESS;
    }
}
