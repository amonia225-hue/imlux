{{-- Un bien déjà enregistré, rendu côté serveur. Le bloc ajouté par le bouton
     « Ajouter un bien » est généré en JavaScript et doit rester identique. --}}
<div class="dp-bien">
    <div class="dp-bien-head">
        <strong>Bien {{ $i + 1 }}</strong>
        <button type="button" class="dp-btn-link" onclick="this.closest('.dp-bien').remove()">Retirer ce bien</button>
    </div>

    <input type="hidden" name="biens[{{ $i }}][id]" value="{{ $bien['id'] ?? '' }}">

    <div class="dp-grid c3">
        <div class="dp-field">
            <label>Site *</label>
            <input name="biens[{{ $i }}][site]" value="{{ $bien['site'] ?? '' }}" required>
        </div>
        <div class="dp-field">
            <label>Type de bien *</label>
            <select name="biens[{{ $i }}][type_bien]">
                @foreach ($types as $cle => $label)
                    <option value="{{ $cle }}" @selected(($bien['type_bien'] ?? '') === $cle)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="dp-field">
            <label>Désignation *</label>
            <input name="biens[{{ $i }}][libelle]" value="{{ $bien['libelle'] ?? '' }}" required>
        </div>
        <div class="dp-field">
            <label>Superficie min (m²)</label>
            <input type="number" min="1" name="biens[{{ $i }}][superficie_min]" value="{{ $bien['superficie_min'] ?? '' }}">
        </div>
        <div class="dp-field">
            <label>Superficie max (m²)</label>
            <input type="number" min="1" name="biens[{{ $i }}][superficie_max]" value="{{ $bien['superficie_max'] ?? '' }}">
        </div>
        <div class="dp-field">
            <label>Superficie utile (m²)</label>
            <input type="number" min="1" name="biens[{{ $i }}][superficie_utile]" value="{{ $bien['superficie_utile'] ?? '' }}">
        </div>
        <div class="dp-field">
            <label>Nombre de pièces</label>
            <input type="number" min="1" name="biens[{{ $i }}][nb_pieces]" value="{{ $bien['nb_pieces'] ?? '' }}">
        </div>
        <div class="dp-field">
            <label>Disponibilité (nombre)</label>
            <input type="number" min="0" name="biens[{{ $i }}][disponibilite]" value="{{ $bien['disponibilite'] ?? '' }}">
        </div>
        <div class="dp-field">
            <label>Prix unitaire (FCFA)</label>
            <input type="number" min="0" name="biens[{{ $i }}][prix_unitaire]" value="{{ $bien['prix_unitaire'] ?? '' }}">
        </div>
    </div>

    <div class="dp-grid c2" style="margin-top:.9rem">
        <div class="dp-field">
            <label>Numéros de lot</label>
            <input name="biens[{{ $i }}][lots]" value="{{ $bien['lots'] ?? '' }}" placeholder="13 ; 14 ; 15">
        </div>
        <div class="dp-field">
            <label>Îlot</label>
            <input name="biens[{{ $i }}][ilot]" value="{{ $bien['ilot'] ?? '' }}">
        </div>
        <div class="dp-field">
            <label>Délai de règlement</label>
            <input name="biens[{{ $i }}][delai_reglement]" value="{{ $bien['delai_reglement'] ?? '' }}" placeholder="12 mois après signature">
        </div>
        <div class="dp-field">
            <label style="text-transform:none;letter-spacing:0;font-weight:500;color:var(--text)">
                <input type="checkbox" name="biens[{{ $i }}][prix_au_m2]" value="1" style="width:auto" @checked(! empty($bien['prix_au_m2']))>
                Le prix indiqué est au m²
            </label>
        </div>
    </div>

    <div style="margin-top:.9rem">
        <label style="display:block;font-size:.74rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.05em;margin-bottom:.4rem">Mode d'acquisition</label>
        <div class="dp-check">
            @foreach ($modes as $cle => $label)
                <label>
                    <input type="checkbox" name="biens[{{ $i }}][modes_acquisition][]" value="{{ $cle }}"
                        @checked(in_array($cle, $bien['modes_acquisition'] ?? [], true))>
                    {{ $label }}
                </label>
            @endforeach
        </div>
    </div>

    <div style="margin-top:.9rem">
        <label style="display:block;font-size:.74rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.05em;margin-bottom:.4rem">Documents disponibles</label>
        <div class="dp-check">
            @foreach ($documents as $cle => $label)
                <label>
                    <input type="checkbox" name="biens[{{ $i }}][documents][]" value="{{ $cle }}"
                        @checked(in_array($cle, $bien['documents'] ?? [], true))>
                    {{ $label }}
                </label>
            @endforeach
        </div>
    </div>

    <div class="dp-field" style="margin-top:.9rem">
        <label>Commentaire</label>
        <textarea name="biens[{{ $i }}][commentaire]">{{ $bien['commentaire'] ?? '' }}</textarea>
    </div>

    {{-- « Suivant » enregistre ce bien et ouvre sa zone photos. Le promoteur ne
         doit pas avoir à penser à sauvegarder : sans identifiant en base, une
         photo n'aurait rien à quoi se rattacher. --}}
    <div class="dp-suivant">
        <button type="button" class="dp-btn dp-btn-primary" data-suivant="{{ $i }}">
            {{ empty($bien['id']) ? 'Suivant — ajouter les photos' : 'Enregistrer les modifications' }}
        </button>
        <span class="dp-etat" data-etat="{{ $i }}"></span>
    </div>

    @include('promoteur.partials.photos-bien', [
        'i' => $i,
        'bien' => $bien,
        'token' => $invitation->token,
        'fichiers' => $fichiersParBien[$bien['id'] ?? 0] ?? [],
    ])
</div>
