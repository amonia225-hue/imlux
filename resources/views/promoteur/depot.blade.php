@extends('public.layout')

@section('title', 'Dépôt de biens — Promoteur')

@section('styles')
    /* La barre de navigation du site est fixe : sans ce dégagement, le titre
       du formulaire passe dessous et devient illisible. */
    .dp-wrap{max-width:1060px;margin:0 auto;padding:6.5rem 1rem 4rem}
    @media(max-width:760px){.dp-wrap{padding-top:8rem}}
    .dp-head{background:linear-gradient(135deg,var(--navy),var(--blue));color:#fff;border-radius:20px;padding:1.8rem 2rem;margin-bottom:1.4rem}
    .dp-head h1{font-family:'Cormorant Garamond',serif;font-size:2rem;font-weight:700;margin:0 0 .4rem}
    .dp-head p{color:rgba(255,255,255,.85);font-size:.95rem;line-height:1.6;margin:0;max-width:62ch}
    .dp-card{background:var(--surface);border:1px solid var(--line);border-radius:16px;padding:1.4rem 1.5rem;margin-bottom:1.1rem}
    .dp-card h2{font-size:1.05rem;font-weight:700;color:var(--ink);margin:0 0 .2rem}
    .dp-card .hint{font-size:.84rem;color:var(--muted);margin:0 0 1rem}
    .dp-grid{display:grid;gap:.9rem;grid-template-columns:1fr}
    @media(min-width:760px){.dp-grid.c2{grid-template-columns:repeat(2,1fr)}.dp-grid.c3{grid-template-columns:repeat(3,1fr)}}
    .dp-field label{display:block;font-size:.74rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.05em;margin-bottom:.35rem}
    .dp-field input,.dp-field select,.dp-field textarea{width:100%;padding:.7rem .85rem;border:1px solid var(--line);border-radius:11px;background:#FBFAF6;color:var(--text);font:inherit;font-size:.92rem}
    .dp-field input:focus,.dp-field select:focus,.dp-field textarea:focus{outline:none;border-color:var(--blue);box-shadow:0 0 0 3px rgba(30,58,140,.12)}
    .dp-field textarea{min-height:70px;resize:vertical}
    .dp-bien{border:1px solid var(--line);border-radius:14px;padding:1.2rem;margin-bottom:1rem;background:#FCFBF8}
    .dp-bien-head{display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;gap:1rem}
    .dp-bien-head strong{font-size:.95rem;color:var(--ink)}
    .dp-check{display:flex;flex-wrap:wrap;gap:.5rem}
    .dp-check label{display:inline-flex;align-items:center;gap:.45rem;padding:.5rem .8rem;border:1px solid var(--line);border-radius:999px;background:#fff;font-size:.85rem;cursor:pointer;text-transform:none;letter-spacing:0;font-weight:500;color:var(--text);margin:0}
    .dp-check input{width:auto;accent-color:var(--blue)}
    .dp-btn{border:none;border-radius:11px;padding:.75rem 1.3rem;font:inherit;font-weight:700;cursor:pointer}
    .dp-btn-primary{background:var(--orange);color:#fff}
    .dp-btn-ghost{background:#fff;color:var(--blue);border:1.5px solid var(--blue)}
    .dp-btn-link{background:none;border:none;color:#B0322A;font-weight:600;cursor:pointer;font-size:.84rem;padding:.3rem}
    .dp-flash{background:#EAF4EC;border:1px solid #BFDCC6;color:#1F6B4C;border-radius:11px;padding:.8rem 1rem;font-size:.9rem;margin-bottom:1rem}
    .dp-err{background:#FDECEA;border:1px solid #F3C9C2;color:#8C2A1C;border-radius:11px;padding:.8rem 1rem;font-size:.9rem;margin-bottom:1rem}
    .dp-err ul{margin:.4rem 0 0 1.1rem}
    .dp-files{display:flex;flex-wrap:wrap;gap:.7rem;margin-top:.8rem}
    .dp-file{border:1px solid var(--line);border-radius:10px;padding:.5rem .7rem;background:#fff;font-size:.82rem;display:flex;align-items:center;gap:.6rem}
    .dp-file img{width:44px;height:44px;object-fit:cover;border-radius:6px}
    .dp-suivant{display:flex;align-items:center;gap:.9rem;flex-wrap:wrap;margin-top:1.1rem;padding-top:1rem;border-top:1px solid var(--line)}
    .dp-etat{font-size:.84rem;color:var(--muted)}
    .dp-etat.ok{color:#1F6B4C;font-weight:600}
    .dp-etat.ko{color:#B0322A;font-weight:600}
    .dp-zone{margin-top:1.1rem;padding-top:1rem;border-top:1px dashed var(--line)}
    .dp-zone-head{display:flex;justify-content:space-between;align-items:center;margin-bottom:.35rem}
    .dp-zone-head strong{font-size:.9rem;color:var(--ink)}
    .dp-zone-count{font-size:.8rem;color:var(--muted)}
    .dp-zone-hint{font-size:.8rem;color:var(--muted);line-height:1.55;margin:0 0 .8rem}
    .dp-drop{display:flex;align-items:center;justify-content:center;padding:1.4rem;border:1.5px dashed var(--blue-soft);border-radius:12px;background:#F8FAFF;cursor:pointer;transition:.15s}
    .dp-drop:hover,.dp-drop.over{background:#EEF3FF;border-color:var(--blue)}
    .dp-drop-txt{font-size:.88rem;color:var(--blue);font-weight:600}
    .dp-progress{margin-top:.8rem}
    .dp-progress-bar{height:6px;border-radius:99px;background:var(--line);overflow:hidden}
    .dp-progress-bar span{display:block;height:100%;width:0;background:var(--orange);transition:width .2s}
    .dp-progress-txt{font-size:.8rem;color:var(--muted);margin-top:.4rem}
    .dp-actions{display:flex;gap:.8rem;flex-wrap:wrap;align-items:center;margin-top:1.2rem}
@endsection

@section('content')
<div class="dp-wrap">

    <div class="dp-head">
        <h1>Déclaration de biens disponibles</h1>
        <p>
            Bonjour {{ $soumission->promoteur }}. Ce formulaire permet de nous transmettre les biens
            que vous avez à disposition. Vous pouvez enregistrer un brouillon et revenir plus tard
            avec le même lien — rien n'est transmis tant que vous n'avez pas cliqué « Transmettre ».
        </p>
    </div>

    @if (session('ok'))
        <div class="dp-flash">{{ session('ok') }}</div>
    @endif

    @if ($errors->any())
        <div class="dp-err">
            <strong>Vérifiez les points suivants :</strong>
            <ul>
                @foreach ($errors->all() as $erreur)
                    <li>{{ $erreur }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('promoteur.depot.enregistrer', $invitation->token) }}" id="dp-form">
        @csrf

        <div class="dp-card">
            <h2>Vos coordonnées</h2>
            <p class="hint">Pour que nous puissions revenir vers vous sur ces biens.</p>
            <div class="dp-grid c2">
                <div class="dp-field">
                    <label for="promoteur">Promoteur / société *</label>
                    <input id="promoteur" name="promoteur" value="{{ old('promoteur', $soumission->promoteur) }}" required>
                </div>
                <div class="dp-field">
                    <label for="contact">Personne à contacter</label>
                    <input id="contact" name="contact" value="{{ old('contact', $soumission->contact) }}">
                </div>
                <div class="dp-field">
                    <label for="telephone">Téléphone</label>
                    <input id="telephone" name="telephone" value="{{ old('telephone', $soumission->telephone) }}" placeholder="+225 ...">
                </div>
                <div class="dp-field">
                    <label for="email">E-mail</label>
                    <input id="email" type="email" name="email" value="{{ old('email', $soumission->email) }}">
                </div>
            </div>
        </div>

        <div class="dp-card">
            <h2>Biens proposés</h2>
            <p class="hint">Un bloc par type de bien. Ajoutez-en autant que nécessaire.</p>

            <div id="dp-biens">
                @php $biens = old('biens') ?: $soumission->biens->map(fn ($b) => [
                    'id' => $b->id,
                    'site' => $b->site,
                    'type_bien' => $b->type_bien,
                    'libelle' => $b->libelle,
                    'superficie_min' => $b->superficie_min,
                    'superficie_max' => $b->superficie_max,
                    'superficie_utile' => $b->superficie_utile,
                    'nb_pieces' => $b->nb_pieces,
                    'disponibilite' => $b->disponibilite,
                    'modes_acquisition' => $b->modes_acquisition ?? [],
                    'prix_unitaire' => $b->prix_unitaire,
                    'prix_au_m2' => $b->prix_au_m2,
                    'delai_reglement' => $b->delai_reglement,
                    'documents' => $b->documents ?? [],
                    'commentaire' => $b->commentaire,
                    'lots' => $b->lots->pluck('numero')->implode(' ; '),
                    'ilot' => $b->lots->first()->ilot ?? '',
                ])->all(); @endphp

                @php $fichiersParBien = $soumission->fichiers->whereNotNull('bien_propose_id')->groupBy('bien_propose_id'); @endphp

                @foreach ($biens as $i => $bien)
                    @include('promoteur.partials.bien', [
                        'i' => $i, 'bien' => $bien, 'types' => $types, 'modes' => $modes,
                        'documents' => $documents, 'invitation' => $invitation,
                        'fichiersParBien' => $fichiersParBien,
                    ])
                @endforeach
            </div>

            <button type="button" class="dp-btn dp-btn-ghost" onclick="dpAjouterBien()">+ Ajouter un bien</button>
        </div>

        <div class="dp-actions">
            <button type="submit" class="dp-btn dp-btn-ghost">Enregistrer le brouillon</button>
            <button type="submit" name="transmettre" value="1" class="dp-btn dp-btn-primary">Transmettre définitivement</button>
            <span style="font-size:.82rem;color:var(--muted)">Après transmission, le formulaire ne sera plus modifiable.</span>
        </div>
    </form>

    {{-- ============ DOCUMENTS GENERAUX ============ --}}
    <div class="dp-card" style="margin-top:1.1rem">
        <h2>Documents généraux</h2>
        <p class="hint">Pièces qui ne concernent aucun bien en particulier : agrément promoteur, plan d'ensemble du site…</p>

        <form method="POST" action="{{ route('promoteur.depot.fichiers', $invitation->token) }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="categorie" value="document">
            <div class="dp-field">
                <label for="fichiers-gen">Fichiers</label>
                <input id="fichiers-gen" type="file" name="fichiers[]" multiple accept=".jpg,.jpeg,.png,.webp,.pdf" required>
            </div>
            <button type="submit" class="dp-btn dp-btn-ghost" style="margin-top:.9rem">Ajouter</button>
        </form>

        @php $generaux = $soumission->fichiers->whereNull('bien_propose_id'); @endphp
        @if ($generaux->isNotEmpty())
            <div class="dp-files">
                @foreach ($generaux as $fichier)
                    <div class="dp-file">
                        @if ($fichier->estImage())
                            <img src="{{ route('promoteur.depot.fichier', [$invitation->token, $fichier]) }}" alt="">
                        @endif
                        <span>
                            <strong>{{ $fichier->nom_original }}</strong><br>
                            <span style="color:var(--muted)">{{ $fichier->tailleLisible() }}</span>
                        </span>
                        <form method="POST" action="{{ route('promoteur.depot.fichiers.supprimer', [$invitation->token, $fichier]) }}">
                            @csrf
                            <button type="submit" class="dp-btn-link">Retirer</button>
                        </form>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

</div>
@endsection

@push('scripts')
<script>
    const DP_TYPES = @json($types);
    const DP_MODES = @json($modes);
    const DP_DOCS  = @json($documents);
    let dpIndex = {{ count($biens) }};

    function dpAjouterBien() {
        const i = dpIndex++;
        const opt = (o) => Object.entries(o).map(([k, v]) => `<option value="${k}">${v}</option>`).join('');
        const chk = (o, champ) => Object.entries(o).map(([k, v]) =>
            `<label><input type="checkbox" name="biens[${i}][${champ}][]" value="${k}"> ${v}</label>`).join('');

        const bloc = document.createElement('div');
        bloc.className = 'dp-bien';
        bloc.innerHTML = `
            <div class="dp-bien-head">
                <strong>Bien ${i + 1}</strong>
                <button type="button" class="dp-btn-link" onclick="this.closest('.dp-bien').remove()">Retirer ce bien</button>
            </div>
            <div class="dp-grid c3">
                <div class="dp-field"><label>Site *</label><input name="biens[${i}][site]" placeholder="Bingerville, Songon…" required></div>
                <div class="dp-field"><label>Type de bien *</label><select name="biens[${i}][type_bien]">${opt(DP_TYPES)}</select></div>
                <div class="dp-field"><label>Désignation *</label><input name="biens[${i}][libelle]" placeholder="Villa duplex 4 pièces" required></div>
                <div class="dp-field"><label>Superficie min (m²)</label><input type="number" name="biens[${i}][superficie_min]" min="1"></div>
                <div class="dp-field"><label>Superficie max (m²)</label><input type="number" name="biens[${i}][superficie_max]" min="1"></div>
                <div class="dp-field"><label>Superficie utile (m²)</label><input type="number" name="biens[${i}][superficie_utile]" min="1"></div>
                <div class="dp-field"><label>Nombre de pièces</label><input type="number" name="biens[${i}][nb_pieces]" min="1"></div>
                <div class="dp-field"><label>Disponibilité (nombre)</label><input type="number" name="biens[${i}][disponibilite]" min="0"></div>
                <div class="dp-field"><label>Prix unitaire (FCFA)</label><input type="number" name="biens[${i}][prix_unitaire]" min="0"></div>
            </div>
            <div class="dp-grid c2" style="margin-top:.9rem">
                <div class="dp-field"><label>Numéros de lot</label><input name="biens[${i}][lots]" placeholder="13 ; 14 ; 15"></div>
                <div class="dp-field"><label>Îlot</label><input name="biens[${i}][ilot]"></div>
                <div class="dp-field"><label>Délai de règlement</label><input name="biens[${i}][delai_reglement]" placeholder="12 mois après signature"></div>
                <div class="dp-field"><label style="text-transform:none;letter-spacing:0;font-weight:500;color:var(--text)"><input type="checkbox" name="biens[${i}][prix_au_m2]" value="1" style="width:auto"> Le prix indiqué est au m²</label></div>
            </div>
            <div style="margin-top:.9rem">
                <label style="display:block;font-size:.74rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.05em;margin-bottom:.4rem">Mode d'acquisition</label>
                <div class="dp-check">${chk(DP_MODES, 'modes_acquisition')}</div>
            </div>
            <div style="margin-top:.9rem">
                <label style="display:block;font-size:.74rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.05em;margin-bottom:.4rem">Documents disponibles</label>
                <div class="dp-check">${chk(DP_DOCS, 'documents')}</div>
            </div>
            <div class="dp-field" style="margin-top:.9rem"><label>Commentaire</label><textarea name="biens[${i}][commentaire]"></textarea></div>
            <input type="hidden" name="biens[${i}][id]" value="">
            <div class="dp-suivant">
                <button type="button" class="dp-btn dp-btn-primary" data-suivant="${i}">Suivant — ajouter les photos</button>
                <span class="dp-etat" data-etat="${i}"></span>
            </div>
            <div class="dp-zone" data-zone="${i}" data-bien-id="" hidden>
                <div class="dp-zone-head">
                    <strong>Photos de ce bien</strong>
                    <span class="dp-zone-count" data-count="${i}">0 / 12</span>
                </div>
                <p class="dp-zone-hint">JPEG, PNG ou WebP. Les photos sont <strong>allégées automatiquement</strong> avant l'envoi : déposez vos originaux sans vous soucier de leur poids. Les PDF partent tels quels, 10 Mo maximum.</p>
                <label class="dp-drop" data-drop="${i}">
                    <input type="file" multiple accept=".jpg,.jpeg,.png,.webp,.pdf" data-input="${i}" hidden>
                    <span class="dp-drop-txt">Cliquez ou déposez vos fichiers ici</span>
                </label>
                <div class="dp-progress" data-progress="${i}" hidden>
                    <div class="dp-progress-bar"><span></span></div>
                    <div class="dp-progress-txt"></div>
                </div>
                <div class="dp-files" data-files="${i}"></div>
            </div>
        `;
        document.getElementById('dp-biens').appendChild(bloc);
    }


    /* ================= Enregistrement d'un bien et dépôt de ses photos =================
       Le promoteur ne doit jamais avoir à « penser à sauvegarder ». « Suivant »
       enregistre le brouillon, récupère l'identifiant du bien et ouvre sa zone
       photos — sans recharger la page, donc sans perdre ce qui est déjà saisi. */

    const DP_URL_ENREGISTRER = @json(route('promoteur.depot.enregistrer', $invitation->token));
    const DP_URL_FICHIERS    = @json(route('promoteur.depot.fichiers', $invitation->token));
    const DP_CSRF            = @json(csrf_token());

    /* L'hébergeur plafonne la requête entière à 64 Mo : on n'envoie jamais un lot,
       toujours un fichier à la fois. */
    const DP_MAX_OCTETS = 10 * 1024 * 1024;
    const DP_LARGEUR_MAX = 2000;   // suffisant pour une annonce en plein écran
    const DP_QUALITE = 0.82;

    const dpQs = (s, r = document) => r.querySelector(s);

    function dpEtat(i, texte, classe = '') {
        const e = dpQs(`[data-etat="${i}"]`);
        if (e) { e.textContent = texte; e.className = 'dp-etat ' + classe; }
    }

    /* Réduit une photo dans le navigateur avant l'envoi. Un original d'appareil
       photo de 40 Mo redescend sous le mégaoctet, sans différence visible à
       l'écran — c'est ce qui rend l'envoi possible depuis un téléphone. */
    async function dpCompresser(fichier) {
        if (!fichier.type.startsWith('image/')) return fichier;

        try {
            const bitmap = await createImageBitmap(fichier);
            const ratio = Math.min(1, DP_LARGEUR_MAX / Math.max(bitmap.width, bitmap.height));
            const c = document.createElement('canvas');
            c.width = Math.round(bitmap.width * ratio);
            c.height = Math.round(bitmap.height * ratio);
            c.getContext('2d').drawImage(bitmap, 0, 0, c.width, c.height);

            const blob = await new Promise((r) => c.toBlob(r, 'image/jpeg', DP_QUALITE));
            if (!blob || blob.size >= fichier.size) return fichier;   // déjà optimisé

            const nom = fichier.name.replace(/\.[^.]+$/, '') + '.jpg';
            return new File([blob], nom, { type: 'image/jpeg' });
        } catch (e) {
            // Navigateur trop ancien : on envoie l'original plutôt que d'échouer.
            return fichier;
        }
    }

    const dpKo = (o) => o >= 1048576 ? (o / 1048576).toFixed(1) + ' Mo' : Math.round(o / 1024) + ' Ko';

    async function dpEnregistrer(i) {
        const form = dpQs('#dp-form');
        const donnees = new FormData(form);
        donnees.delete('transmettre');

        dpEtat(i, 'Enregistrement…');

        const r = await fetch(DP_URL_ENREGISTRER, {
            method: 'POST',
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            body: donnees,
        });

        if (!r.ok) {
            const err = await r.json().catch(() => ({}));
            const premier = err.errors ? Object.values(err.errors)[0][0] : 'Vérifiez les champs obligatoires.';
            dpEtat(i, premier, 'ko');
            return null;
        }

        const data = await r.json();

        // Les identifiants attribués sont réinjectés dans le formulaire, sinon
        // le prochain enregistrement recréerait des biens en double.
        data.biens.forEach((b) => {
            const champ = dpQs(`input[name="biens[${b.ordre}][id]"]`);
            if (champ) champ.value = b.id;
            const zone = dpQs(`[data-zone="${b.ordre}"]`);
            if (zone) { zone.dataset.bienId = b.id; zone.hidden = false; }
        });

        dpEtat(i, 'Bien enregistré — ajoutez ses photos ci-dessous.', 'ok');
        return data;
    }

    async function dpEnvoyer(i, fichiers) {
        const zone = dpQs(`[data-zone="${i}"]`);
        const bienId = zone?.dataset.bienId;
        if (!bienId) { dpEtat(i, 'Enregistrez d\'abord ce bien.', 'ko'); return; }

        const prog = dpQs(`[data-progress="${i}"]`);
        const barre = dpQs('.dp-progress-bar span', prog);
        const texte = dpQs('.dp-progress-txt', prog);
        prog.hidden = false;

        let n = 0;
        for (const brut of fichiers) {
            n++;
            texte.textContent = `Préparation de ${brut.name} (${n}/${fichiers.length})…`;

            const fichier = await dpCompresser(brut);

            if (fichier.size > DP_MAX_OCTETS) {
                texte.textContent = `${brut.name} dépasse 10 Mo même après compression — ignoré.`;
                continue;
            }

            const allege = fichier !== brut ? ` (${dpKo(brut.size)} → ${dpKo(fichier.size)})` : '';
            texte.textContent = `Envoi de ${brut.name}${allege} — ${n}/${fichiers.length}`;
            barre.style.width = Math.round(((n - 1) / fichiers.length) * 100) + '%';

            const donnees = new FormData();
            donnees.append('_token', DP_CSRF);
            donnees.append('bien_propose_id', bienId);
            donnees.append('categorie', fichier.type === 'application/pdf' ? 'document' : 'photo');
            donnees.append('fichiers[]', fichier);

            const r = await fetch(DP_URL_FICHIERS, {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                body: donnees,
            });

            if (!r.ok) {
                const err = await r.json().catch(() => ({}));
                texte.textContent = err.message || `Échec sur ${brut.name}.`;
                continue;
            }

            const data = await r.json();
            data.fichiers.forEach((f) => dpAjouterVignette(i, f));
        }

        barre.style.width = '100%';
        texte.textContent = 'Terminé.';
        setTimeout(() => { prog.hidden = true; barre.style.width = '0'; }, 2500);
        dpMajCompteur(i);
    }

    function dpAjouterVignette(i, f) {
        const bloc = document.createElement('div');
        bloc.className = 'dp-file';
        bloc.dataset.file = f.id;
        bloc.innerHTML = `${f.image ? `<img src="${f.url}" alt="">` : ''}
            <span><strong>${f.nom}</strong><br><span style="color:var(--muted)">${f.taille}</span></span>
            <button type="button" class="dp-btn-link" data-supprimer="${f.suppression}">Retirer</button>`;
        dpQs(`[data-files="${i}"]`).appendChild(bloc);
    }

    function dpMajCompteur(i) {
        const n = dpQs(`[data-files="${i}"]`).querySelectorAll('.dp-file').length;
        const c = dpQs(`[data-count="${i}"]`);
        if (c) c.textContent = `${n} / 12`;
    }

    /* Délégation : les blocs de biens sont créés dynamiquement, on ne peut pas
       attacher les écouteurs à l'avance. */
    document.addEventListener('click', async (e) => {
        const suivant = e.target.closest('[data-suivant]');
        if (suivant) {
            suivant.disabled = true;
            await dpEnregistrer(suivant.dataset.suivant);
            suivant.disabled = false;
            suivant.textContent = 'Enregistrer les modifications';
            return;
        }

        const drop = e.target.closest('[data-drop]');
        if (drop) { dpQs(`[data-input="${drop.dataset.drop}"]`).click(); return; }

        const sup = e.target.closest('[data-supprimer]');
        if (sup) {
            const r = await fetch(sup.dataset.supprimer, {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': DP_CSRF },
            });
            if (r.ok) {
                const bloc = sup.closest('.dp-file');
                const zone = bloc.closest('.dp-zone');
                bloc.remove();
                if (zone) dpMajCompteur(zone.dataset.zone);
            }
        }
    });

    document.addEventListener('change', (e) => {
        const input = e.target.closest('[data-input]');
        if (input && input.files.length) {
            dpEnvoyer(input.dataset.input, [...input.files]);
            input.value = '';
        }
    });

    ['dragover', 'dragleave', 'drop'].forEach((type) => {
        document.addEventListener(type, (e) => {
            const drop = e.target.closest('[data-drop]');
            if (!drop) return;
            e.preventDefault();
            drop.classList.toggle('over', type === 'dragover');
            if (type === 'drop' && e.dataTransfer.files.length) {
                dpEnvoyer(drop.dataset.drop, [...e.dataTransfer.files]);
            }
        });
    });

    // Un formulaire vide n'aide personne : on ouvre sur un premier bloc.
    if (dpIndex === 0) { dpAjouterBien(); }
</script>
@endpush
