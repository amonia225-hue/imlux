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

                @foreach ($biens as $i => $bien)
                    @include('promoteur.partials.bien', ['i' => $i, 'bien' => $bien, 'types' => $types, 'modes' => $modes, 'documents' => $documents])
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

    {{-- ============ PHOTOS PAR BIEN ============ --}}
    <div class="dp-card" style="margin-top:1.4rem">
        <h2>Photos et documents</h2>
        <p class="hint">
            JPEG, PNG, WebP ou PDF, 8 Mo maximum par fichier. Chaque photo se rattache au bien
            concerné ; les pièces qui valent pour tout le site vont dans le dernier bloc.
            Ces fichiers ne sont visibles que par notre bureau d'études.
        </p>

        @forelse ($soumission->biens as $bien)
            <div class="dp-bien" style="margin-bottom:1.1rem">
                <div class="dp-bien-head">
                    <strong>{{ $bien->libelle }} <span style="font-weight:400;color:var(--muted)">— {{ $bien->site }}</span></strong>
                </div>

                <form method="POST" action="{{ route('promoteur.depot.fichiers', $invitation->token) }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="bien_propose_id" value="{{ $bien->id }}">
                    <div class="dp-grid c2">
                        <div class="dp-field">
                            <label for="cat-{{ $bien->id }}">Type</label>
                            <select id="cat-{{ $bien->id }}" name="categorie">
                                <option value="photo">Photo du bien</option>
                                <option value="document">Document (plan, ACD, agrément…)</option>
                            </select>
                        </div>
                        <div class="dp-field">
                            <label for="fic-{{ $bien->id }}">Fichiers</label>
                            <input id="fic-{{ $bien->id }}" type="file" name="fichiers[]" multiple accept=".jpg,.jpeg,.png,.webp,.pdf" required>
                        </div>
                    </div>
                    <button type="submit" class="dp-btn dp-btn-ghost" style="margin-top:.9rem">Ajouter à ce bien</button>
                </form>

                @if ($bien->fichiers->isNotEmpty())
                    <div class="dp-files">
                        @foreach ($bien->fichiers as $fichier)
                            <div class="dp-file">
                                @if ($fichier->estImage())
                                    <img src="{{ route('promoteur.depot.fichier', [$invitation->token, $fichier]) }}" alt="">
                                @endif
                                <span>
                                    <strong>{{ $fichier->nom_original }}</strong><br>
                                    <span style="color:var(--muted)">{{ $fichier->categorie }} · {{ $fichier->tailleLisible() }}</span>
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
        @empty
            <p style="font-size:.9rem;color:var(--muted)">
                Ajoutez d'abord un bien ci-dessus, puis enregistrez le brouillon : vous pourrez
                ensuite y joindre ses photos.
            </p>
        @endforelse

        {{-- Un bien tout juste ajouté n'a pas encore d'identifiant en base : il faut enregistrer
             le brouillon avant de pouvoir lui rattacher un fichier. --}}
        <div style="background:#FCFBF8;border:1px solid var(--line);border-radius:11px;padding:.9rem 1.1rem;font-size:.85rem;color:var(--muted);margin-top:.4rem">
            Vous venez d'ajouter un bien ? Enregistrez le brouillon pour qu'il apparaisse ici et
            puisse recevoir ses photos.
        </div>
    </div>

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
        `;
        document.getElementById('dp-biens').appendChild(bloc);
    }

    // Un formulaire vide n'aide personne : on ouvre sur un premier bloc.
    if (dpIndex === 0) { dpAjouterBien(); }
</script>
@endpush
