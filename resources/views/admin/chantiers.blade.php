<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>IM'LUX — Avancement des travaux</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('image/logo.jpeg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root{
            --bg:#f6f4ee;--surface:#ffffff;--surface2:#f3f1e9;--border:#e6e0d2;
            --text:#1d2b22;--muted:#6b7770;--accent:#a8801e;--success:#1f8a5a;--warning:#b9831f;--danger:#c2412f;
            --radius:16px;--radius-sm:12px;
        }
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:'Inter',sans-serif;background:var(--bg);color:var(--text);min-height:100vh}
        .page{max-width:1200px;margin:0 auto;padding:1rem}
        .topbar{display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem;margin-bottom:1rem}
        .topbar a.back{color:var(--muted);text-decoration:none;font-weight:600;font-size:.88rem;border:1px solid var(--border);padding:.5rem .9rem;border-radius:var(--radius-sm)}
        .topbar a.back:hover{color:var(--text);border-color:var(--accent)}
        .hero{background:linear-gradient(135deg,#123d2c,#1f6b4c 55%,#2e8b5f);border:1px solid rgba(200,162,74,.25);border-radius:22px;padding:1.4rem 1.6rem;box-shadow:0 20px 40px rgba(18,61,44,.45)}
        .hero h1{font-family:'Cormorant Garamond',serif;font-size:1.9rem;font-weight:700}
        .hero p{color:rgba(255,255,255,.8);margin-top:.3rem;font-size:.92rem}
        .panel{background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);padding:1.2rem;margin-top:1rem;box-shadow:0 4px 18px rgba(31,107,76,.07)}
        .panel h3{font-size:1.1rem;font-weight:700;margin-bottom:.3rem}
        .panel .subtitle{color:var(--muted);font-size:.85rem;margin-bottom:1rem}
        label{display:block;font-size:.74rem;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:.04em;margin-bottom:.35rem}
        input,select,textarea{width:100%;padding:.7rem .8rem;border:1px solid var(--border);border-radius:var(--radius-sm);background:#f3f1e9;color:var(--text);font:inherit;font-size:.9rem}
        input:focus,select:focus,textarea:focus{outline:none;border-color:var(--accent);box-shadow:0 0 0 3px rgba(200,162,74,.15)}
        textarea{min-height:70px;resize:vertical}
        .form-grid{display:grid;gap:.8rem;grid-template-columns:1fr}
        @media(min-width:720px){.form-grid{grid-template-columns:repeat(2,1fr)}}
        .btn{border:none;border-radius:var(--radius-sm);padding:.7rem 1.1rem;font:inherit;font-weight:800;color:#1a1206;background:linear-gradient(135deg,#e8ce7e,#c8a24a 55%,#9c7b2e);cursor:pointer;transition:transform .15s}
        .btn:hover{transform:translateY(-1px)}
        .btn-sm{padding:.4rem .7rem;font-size:.8rem;border-radius:10px}
        .icon-btn{border:1px solid var(--border);background:#f3f1e9;color:var(--muted);border-radius:8px;padding:.3rem .6rem;font:inherit;font-size:.78rem;font-weight:600;cursor:pointer;text-decoration:none}
        .icon-btn:hover{color:var(--text);border-color:var(--accent)}
        .icon-btn.danger:hover{color:var(--danger);border-color:rgba(248,113,113,.4)}
        .flash{background:rgba(52,211,153,.1);border:1px solid rgba(52,211,153,.25);color:var(--success);border-radius:var(--radius-sm);padding:.65rem .8rem;font-size:.88rem;margin-bottom:.8rem}
        .errors{background:rgba(248,113,113,.1);border:1px solid rgba(248,113,113,.25);color:var(--danger);border-radius:var(--radius-sm);padding:.65rem .8rem;font-size:.88rem;margin-bottom:.8rem}
        .errors ul{margin:0;padding-left:1rem}
        .prog-head{display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:.6rem;margin-bottom:.4rem}
        .prog-head .glob{font-size:.85rem;color:var(--muted);font-weight:600}
        .progress{width:100%;height:10px;border-radius:999px;background:var(--surface2);overflow:hidden}
        .progress-bar{height:100%;border-radius:inherit;background:linear-gradient(90deg,#c8a24a,#2e8b5f);transition:width .4s}
        .etape{display:grid;grid-template-columns:80px 1fr auto;gap:1rem;align-items:center;padding:.9rem 0;border-bottom:1px solid var(--border)}
        .etape:last-child{border-bottom:none}
        .etape .thumb{width:80px;height:64px;border-radius:10px;object-fit:cover;background:var(--surface2);display:flex;align-items:center;justify-content:center;color:var(--muted);font-size:.7rem}
        .etape .title{font-weight:700}
        .etape .desc{color:var(--muted);font-size:.82rem;margin-top:.15rem}
        .badge{display:inline-block;border-radius:999px;padding:.2rem .55rem;font-size:.72rem;font-weight:700}
        .b-green{background:rgba(52,211,153,.12);color:var(--success)}
        .b-yellow{background:rgba(251,191,36,.12);color:var(--warning)}
        .b-muted{background:rgba(200,162,74,.12);color:var(--muted)}
        .etape-meta{display:flex;align-items:center;gap:.5rem;flex-wrap:wrap;margin-top:.3rem}
        .empty{text-align:center;color:var(--muted);padding:1.2rem;border:1px dashed var(--border);border-radius:var(--radius-sm);font-size:.88rem}
        details.edit{margin-top:.5rem}
        details.edit summary{cursor:pointer;color:var(--accent);font-size:.8rem;font-weight:600;list-style:none}
        details.edit[open] summary{margin-bottom:.6rem}
    </style>
</head>
<body>
<div class="page">
    <div class="topbar">
        <div style="display:flex;align-items:center;gap:.6rem">
            <img src="{{ asset('image/logo.jpeg') }}" alt="IM'LUX" style="width:42px;height:42px;border-radius:10px;object-fit:cover;background:#fbf7ee;padding:2px">
            <span style="font-family:'Cormorant Garamond',serif;font-size:1.4rem;font-weight:700;background:linear-gradient(120deg,#9c7b2e,#c8a24a 35%,#1f6b4c);-webkit-background-clip:text;-webkit-text-fill-color:transparent">PROJET IM'LUX</span>
        </div>
        <a class="back" href="{{ route('admin.dashboard') }}">&#8592; Tableau de bord</a>
    </div>

    <section class="hero">
        <h1>🏗️ Avancement des travaux</h1>
        <p>Gérez les étapes de chantier par programme. Les souscripteurs voient cet avancement dans leur application mobile.</p>
    </section>

    @if(session('success'))<div class="flash" style="margin-top:1rem">{{ session('success') }}</div>@endif
    @if($errors->any())<div class="errors" style="margin-top:1rem"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif

    {{-- Ajout d'une étape --}}
    <div class="panel">
        <h3>Ajouter une étape</h3>
        <p class="subtitle">Définissez une étape de chantier avec son pourcentage d'avancement et une photo facultative.</p>
        <form method="POST" action="{{ route('chantiers.etapes.store') }}" enctype="multipart/form-data" class="form-grid">
            @csrf
            <div>
                <label>Programme</label>
                <select name="programme_id" required>
                    <option value="">Sélectionner...</option>
                    @foreach($programmes as $p)<option value="{{ $p->id }}">{{ $p->name }}</option>@endforeach
                </select>
            </div>
            <div><label>Titre de l'étape</label><input name="title" placeholder="Ex: Fondations, Gros œuvre, Toiture..." required></div>
            <div><label>Avancement (%)</label><input name="progress" type="number" min="0" max="100" value="0" required></div>
            <div><label>Ordre d'affichage</label><input name="ordre" type="number" min="0" value="0"></div>
            <div><label>Date prévue</label><input name="date_prevue" type="date"></div>
            <div><label>Date réalisée</label><input name="date_realisee" type="date"></div>
            <div style="grid-column:1/-1"><label>Description</label><textarea name="description" placeholder="Détails de l'étape..."></textarea></div>
            <div style="grid-column:1/-1"><label>Photo (facultatif)</label><input name="photo" type="file" accept="image/*"></div>
            <div><button class="btn" type="submit">Ajouter l'étape</button></div>
        </form>
    </div>

    {{-- Liste par programme --}}
    @foreach($programmes as $programme)
        <div class="panel">
            <div class="prog-head">
                <h3>{{ $programme->name }} <span style="color:var(--muted);font-weight:500;font-size:.85rem">— {{ $programme->location }}</span></h3>
                <span class="glob">Avancement global : <strong style="color:var(--accent)">{{ $programme->avancementGlobal() }}%</strong></span>
            </div>
            <div class="progress"><div class="progress-bar" style="width:{{ $programme->avancementGlobal() }}%"></div></div>

            <div style="margin-top:1rem">
                @forelse($programme->etapes as $etape)
                    <div class="etape">
                        @if($etape->photo)
                            <img class="thumb" src="{{ asset('storage/'.$etape->photo) }}" alt="">
                        @else
                            <div class="thumb">Aucune<br>photo</div>
                        @endif
                        <div>
                            <div class="title">{{ $etape->title }}</div>
                            @if($etape->description)<div class="desc">{{ $etape->description }}</div>@endif
                            <div class="etape-meta">
                                @if($etape->status==='termine')<span class="badge b-green">Terminé</span>
                                @elseif($etape->status==='en_cours')<span class="badge b-yellow">En cours</span>
                                @else<span class="badge b-muted">À venir</span>@endif
                                <span style="font-size:.8rem;color:var(--muted)">{{ $etape->progress }}%</span>
                                @if($etape->date_realisee)<span style="font-size:.78rem;color:var(--muted)">• réalisée le {{ $etape->date_realisee->format('d/m/Y') }}</span>
                                @elseif($etape->date_prevue)<span style="font-size:.78rem;color:var(--muted)">• prévue le {{ $etape->date_prevue->format('d/m/Y') }}</span>@endif
                            </div>
                            <div class="progress" style="height:6px;margin-top:.5rem;max-width:240px"><div class="progress-bar" style="width:{{ $etape->progress }}%"></div></div>

                            <details class="edit">
                                <summary>Modifier</summary>
                                <form method="POST" action="{{ route('chantiers.etapes.update', $etape) }}" enctype="multipart/form-data" class="form-grid">
                                    @csrf
                                    <input type="hidden" name="programme_id" value="{{ $programme->id }}">
                                    <div><label>Titre</label><input name="title" value="{{ $etape->title }}" required></div>
                                    <div><label>Avancement (%)</label><input name="progress" type="number" min="0" max="100" value="{{ $etape->progress }}" required></div>
                                    <div><label>Date prévue</label><input name="date_prevue" type="date" value="{{ $etape->date_prevue?->format('Y-m-d') }}"></div>
                                    <div><label>Date réalisée</label><input name="date_realisee" type="date" value="{{ $etape->date_realisee?->format('Y-m-d') }}"></div>
                                    <div><label>Ordre</label><input name="ordre" type="number" min="0" value="{{ $etape->ordre }}"></div>
                                    <div><label>Remplacer la photo</label><input name="photo" type="file" accept="image/*"></div>
                                    <div style="grid-column:1/-1"><label>Description</label><textarea name="description">{{ $etape->description }}</textarea></div>
                                    <div><button class="btn btn-sm" type="submit">Enregistrer</button></div>
                                </form>
                            </details>
                        </div>
                        <div>
                            <form method="POST" action="{{ route('chantiers.etapes.destroy', $etape) }}" onsubmit="return confirm('Supprimer cette étape ?')">
                                @csrf
                                <button class="icon-btn danger" type="submit">Supprimer</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="empty">Aucune étape définie pour ce programme.</div>
                @endforelse
            </div>
        </div>
    @endforeach
</div>
</body>
</html>
