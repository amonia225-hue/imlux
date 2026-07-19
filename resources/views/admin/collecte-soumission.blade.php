<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dépôt de {{ $soumission->promoteur }} — Lorny Conseils Management</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('image/logo.jpeg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,500;1,700&family=Jost:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root{
            --bg:#EEF1F7;--surface:#ffffff;--surface2:#F4F6FB;--border:#E2E7F0;
            --text:#0d1a33;--muted:#5b647a;--accent:#ED8B1C;--accent2:#C9710E;
            --blue:#1E40AF;--blue2:#3B5BDB;--success:#1f8a5a;--warning:#C9710E;--danger:#c2412f;
            --radius:16px;--radius-sm:12px;
        }
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:'Jost',sans-serif;background:var(--bg);color:var(--text)}
        .page{max-width:1060px;margin:0 auto;padding:1rem 1rem 4rem}
        .topbar{display:flex;justify-content:space-between;align-items:center;gap:1rem;margin-bottom:1rem;flex-wrap:wrap}
        .topbar a.back{color:var(--muted);text-decoration:none;font-weight:600;font-size:.88rem;border:1px solid var(--border);padding:.5rem .9rem;border-radius:var(--radius-sm)}
        .hero{background:linear-gradient(120deg,#0B1426 0%,#16329B 68%,#1E40AF 100%);border-radius:22px;padding:1.4rem 1.6rem;color:#fff}
        .hero h1{font-family:'Playfair Display',serif;font-size:1.8rem}
        .hero p{color:rgba(255,255,255,.85);font-size:.9rem;margin-top:.35rem}
        .panel{background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);padding:1.2rem;margin-top:1rem}
        .panel h3{font-size:1.05rem;font-weight:700;margin-bottom:.6rem}
        .grid{display:grid;gap:.6rem 1.4rem;grid-template-columns:1fr}
        @media(min-width:700px){.grid.c2{grid-template-columns:repeat(2,1fr)}.grid.c3{grid-template-columns:repeat(3,1fr)}}
        .kv{font-size:.88rem}
        .kv span{display:block;font-size:.7rem;text-transform:uppercase;letter-spacing:.05em;color:var(--muted);font-weight:700;margin-bottom:.15rem}
        .bien{border:1px solid var(--border);border-radius:14px;padding:1rem;margin-bottom:.9rem;background:var(--surface2)}
        .bien h4{font-size:1rem;font-weight:700;margin-bottom:.7rem}
        .puce{display:inline-block;font-size:.74rem;font-weight:600;padding:.2rem .6rem;border-radius:999px;background:var(--surface2);color:var(--muted);margin:0 .3rem .3rem 0}
        .puce.on{background:rgba(31,138,90,.12);color:var(--success)}
        .fichiers{display:flex;flex-wrap:wrap;gap:.7rem;margin-top:.7rem}
        .fichier{border:1px solid var(--border);border-radius:10px;overflow:hidden;background:#fff;width:150px;text-decoration:none;color:var(--text)}
        .fichier img{width:100%;height:100px;object-fit:cover;display:block}
        .fichier .nom{padding:.45rem .55rem;font-size:.76rem;line-height:1.3;word-break:break-word}
        .fichier .apercu{display:block;text-decoration:none;color:inherit}
        .fichier .dl{display:block;padding:.35rem .55rem;border-top:1px solid var(--border);font-size:.72rem;font-weight:700;color:var(--accent);text-decoration:none;text-align:center}
        .fichier .dl:hover{background:rgba(237,139,28,.08)}
        .zip{display:inline-flex;align-items:center;gap:.4rem;background:var(--accent);color:#fff;text-decoration:none;font-weight:800;font-size:.84rem;padding:.6rem 1rem;border-radius:var(--radius-sm)}
        .zip.ghost{background:#fff;color:var(--blue);border:1.5px solid var(--border);font-size:.78rem;padding:.4rem .8rem}
        label{display:block;font-size:.74rem;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:.04em;margin-bottom:.35rem}
        textarea{width:100%;padding:.7rem .8rem;border:1px solid var(--border);border-radius:var(--radius-sm);background:var(--surface2);font:inherit;font-size:.9rem;min-height:80px;resize:vertical}
        .btn{border:none;border-radius:var(--radius-sm);padding:.7rem 1.2rem;font:inherit;font-weight:800;cursor:pointer}
        .btn-ok{background:var(--accent);color:#fff}
        .btn-no{background:#fff;color:var(--danger);border:1.5px solid rgba(194,65,47,.4)}
        .err{background:rgba(194,65,47,.08);border:1px solid rgba(194,65,47,.25);color:var(--danger);border-radius:var(--radius-sm);padding:.65rem .8rem;font-size:.88rem;margin-bottom:.8rem}
        .tag{display:inline-block;font-size:.74rem;font-weight:700;padding:.25rem .7rem;border-radius:999px}
        .tag.ok{background:rgba(31,138,90,.12);color:var(--success)}
        .tag.no{background:rgba(194,65,47,.1);color:var(--danger)}
        .tag.att{background:rgba(185,131,31,.12);color:var(--warning)}
    </style>
</head>
<body>
<div class="page">
    <div class="topbar">
        <a class="back" href="{{ route('admin.collecte.index') }}">← Collecte promoteurs</a>
        <span style="display:flex;align-items:center;gap:.7rem;flex-wrap:wrap">
            {{-- Trente photos ne se récupèrent pas une par une : l'archive est
                 rangée en un dossier par bien pour rester exploitable. --}}
            @if ($soumission->fichiers->isNotEmpty())
                <a class="zip" href="{{ route('admin.collecte.archive', $soumission) }}">
                    ⬇ Télécharger les {{ $soumission->fichiers->count() }} pièce(s) — ZIP
                </a>
            @endif
            <span class="tag {{ $soumission->statut === 'validee' ? 'ok' : ($soumission->statut === 'rejetee' ? 'no' : 'att') }}">
                {{ $soumission->statutLabel() }}
            </span>
        </span>
    </div>

    <div class="hero">
        <h1>{{ $soumission->promoteur }}</h1>
        <p>
            {{ $soumission->biens->count() }} bien(s) déclaré(s) ·
            transmis le {{ $soumission->soumise_le?->format('d/m/Y à H:i') ?? '—' }}
        </p>
    </div>

    @if ($errors->any())<div class="err" style="margin-top:1rem">{{ $errors->first() }}</div>@endif

    <div class="panel">
        <h3>Contact</h3>
        <div class="grid c3">
            <div class="kv"><span>Personne à contacter</span>{{ $soumission->contact ?: '—' }}</div>
            <div class="kv"><span>Téléphone</span>{{ $soumission->telephone ?: '—' }}</div>
            <div class="kv"><span>E-mail</span>{{ $soumission->email ?: '—' }}</div>
        </div>
    </div>

    @foreach ($soumission->biens as $bien)
        <div class="panel">
            <div class="bien">
                <h4>{{ $bien->libelle }} <span style="font-weight:400;color:var(--muted)">— {{ $bien->site }} · {{ $bien->typeLabel() }}</span></h4>

                <div class="grid c3">
                    <div class="kv"><span>Superficie</span>{{ $bien->superficieLabel() }}</div>
                    <div class="kv"><span>Superficie utile</span>{{ $bien->superficie_utile ? $bien->superficie_utile.' m²' : '—' }}</div>
                    <div class="kv"><span>Pièces</span>{{ $bien->nb_pieces ?: '—' }}</div>
                    <div class="kv"><span>Disponibilité</span>{{ $bien->disponibilite !== null ? $bien->disponibilite.' unité(s)' : '—' }}</div>
                    <div class="kv"><span>Prix</span>{{ $bien->prixLabel() }}</div>
                    <div class="kv"><span>Délai de règlement</span>{{ $bien->delai_reglement ?: '—' }}</div>
                </div>

                <div style="margin-top:.9rem">
                    <label>Mode d'acquisition</label>
                    @forelse ($bien->modes_acquisition ?? [] as $mode)
                        <span class="puce on">{{ \App\Models\BienPropose::MODES[$mode] ?? $mode }}</span>
                    @empty
                        <span class="puce">non précisé</span>
                    @endforelse
                </div>

                <div style="margin-top:.9rem">
                    <label>Documents disponibles</label>
                    @foreach (\App\Models\BienPropose::DOCUMENTS as $cle => $libelle)
                        @php $present = in_array($cle, $bien->documents ?? [], true); @endphp
                        <span class="puce {{ $present ? 'on' : '' }}">{{ $present ? '✓' : '✕' }} {{ $libelle }}</span>
                    @endforeach
                </div>

                @if ($bien->lots->isNotEmpty())
                    <div style="margin-top:.9rem">
                        <label>Lots ({{ $bien->lots->count() }})</label>
                        @foreach ($bien->lots as $lot)
                            <span class="puce">{{ $lot->numero }}@if ($lot->ilot) · îlot {{ $lot->ilot }}@endif</span>
                        @endforeach
                    </div>
                @endif

                @if ($bien->commentaire)
                    <div style="margin-top:.9rem" class="kv"><span>Commentaire du promoteur</span>{{ $bien->commentaire }}</div>
                @endif

                @if ($bien->fichiers->isNotEmpty())
                    <div style="display:flex;align-items:center;justify-content:space-between;gap:.7rem;margin-top:.9rem">
                        <label style="margin:0">Pièces de ce bien ({{ $bien->fichiers->count() }})</label>
                        <a class="zip ghost" href="{{ route('admin.collecte.archive', $soumission) }}?bien={{ $bien->id }}">
                            ⬇ Tout télécharger
                        </a>
                    </div>
                    <div class="fichiers">
                        @foreach ($bien->fichiers as $fichier)
                            <div class="fichier">
                                <a class="apercu" href="{{ route('admin.collecte.fichier', $fichier) }}" target="_blank" rel="noopener">
                                    @if ($fichier->estImage())
                                        <img src="{{ route('admin.collecte.fichier', $fichier) }}" alt="{{ $fichier->nom_original }}">
                                    @endif
                                    <div class="nom">{{ $fichier->nom_original }}<br><span style="color:var(--muted)">{{ $fichier->tailleLisible() }}</span></div>
                                </a>
                                <a class="dl" href="{{ route('admin.collecte.fichier', $fichier) }}?telecharger=1">⬇ Télécharger</a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    @endforeach

    @php $generaux = $soumission->fichiers->whereNull('bien_propose_id'); @endphp
    @if ($generaux->isNotEmpty())
        <div class="panel">
            <h3>Pièces jointes générales</h3>
            <div class="fichiers">
                @foreach ($generaux as $fichier)
                    <div class="fichier">
                        <a class="apercu" href="{{ route('admin.collecte.fichier', $fichier) }}" target="_blank" rel="noopener">
                            @if ($fichier->estImage())
                                <img src="{{ route('admin.collecte.fichier', $fichier) }}" alt="{{ $fichier->nom_original }}">
                            @endif
                            <div class="nom">{{ $fichier->nom_original }}<br><span style="color:var(--muted)">{{ $fichier->tailleLisible() }}</span></div>
                        </a>
                        <a class="dl" href="{{ route('admin.collecte.fichier', $fichier) }}?telecharger=1">⬇ Télécharger</a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if ($soumission->estTraitee())
        <div class="panel">
            <h3>Décision</h3>
            <p style="font-size:.9rem;color:var(--muted)">
                {{ $soumission->statutLabel() }} le {{ $soumission->traitee_le?->format('d/m/Y à H:i') }}
                @if ($soumission->traitePar) par {{ $soumission->traitePar->name }}@endif.
            </p>
            @if ($soumission->note_admin)
                <p style="margin-top:.6rem;font-size:.9rem">{{ $soumission->note_admin }}</p>
            @endif
        </div>
    @else
        <div class="panel">
            <h3>Traiter ce dépôt</h3>
            <form method="POST" action="{{ route('admin.collecte.traiter', $soumission) }}">
                @csrf
                <label for="note_admin">Note (visible par le promoteur en cas de rejet)</label>
                <textarea id="note_admin" name="note_admin" placeholder="Précisions, pièces manquantes…"></textarea>
                <div style="display:flex;gap:.8rem;margin-top:.9rem;flex-wrap:wrap">
                    <button type="submit" name="decision" value="validee" class="btn btn-ok">Valider le dépôt</button>
                    <button type="submit" name="decision" value="rejetee" class="btn btn-no">Rejeter</button>
                </div>
            </form>
        </div>
    @endif
</div>
</body>
</html>
