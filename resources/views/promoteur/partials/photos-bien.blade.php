{{-- Zone photos d'un bien. Rendue vide tant que le bien n'est pas enregistré ;
     le bouton « Suivant » l'active en injectant l'identifiant reçu du serveur. --}}
<div class="dp-zone" data-zone="{{ $i }}" data-bien-id="{{ $bien['id'] ?? '' }}" @if (empty($bien['id'])) hidden @endif>
    <div class="dp-zone-head">
        <strong>Photos de ce bien</strong>
        <span class="dp-zone-count" data-count="{{ $i }}">{{ count($fichiers ?? []) }} / 12</span>
    </div>

    <p class="dp-zone-hint">
        JPEG, PNG ou WebP. Les photos sont <strong>allégées automatiquement</strong> avant l'envoi :
        vous pouvez déposer des originaux d'appareil photo sans vous soucier de leur poids.
        Les PDF (plans, ACD) partent tels quels, 10 Mo maximum.
    </p>

    <label class="dp-drop" data-drop="{{ $i }}">
        <input type="file" multiple accept=".jpg,.jpeg,.png,.webp,.pdf" data-input="{{ $i }}" hidden>
        <span class="dp-drop-txt">Cliquez ou déposez vos fichiers ici</span>
    </label>

    <div class="dp-progress" data-progress="{{ $i }}" hidden>
        <div class="dp-progress-bar"><span></span></div>
        <div class="dp-progress-txt"></div>
    </div>

    <div class="dp-files" data-files="{{ $i }}">
        @foreach ($fichiers ?? [] as $fichier)
            <div class="dp-file" data-file="{{ $fichier->id }}">
                @if ($fichier->estImage())
                    <img src="{{ route('promoteur.depot.fichier', [$token, $fichier]) }}" alt="">
                @endif
                <span>
                    <strong>{{ $fichier->nom_original }}</strong><br>
                    <span style="color:var(--muted)">{{ $fichier->tailleLisible() }}</span>
                </span>
                <button type="button" class="dp-btn-link"
                        data-supprimer="{{ route('promoteur.depot.fichiers.supprimer', [$token, $fichier]) }}">Retirer</button>
            </div>
        @endforeach
    </div>
</div>
