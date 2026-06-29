<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lorny Conseils Management — Administration</title>
    <link rel="icon" type="image/png" href="{{ asset('image/lorny.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,500;1,700&family=Jost:wght@300;400;500;600;700&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --bg: #EEF1F7;
            --surface: #ffffff;
            --surface2: #F4F6FB;
            --border: #E2E7F0;
            --text: #0d1a33;
            --muted: #5b647a;
            --accent: #ED8B1C;
            --accent2: #C9710E;
            --blue: #1E40AF; --blue2: #3B5BDB; --gold: #ED8B1C; --grey: #5A5E66; --mono: 'Space Mono', monospace;
            --success: #1f8a5a;
            --success-bg: rgba(31,138,90,.12);
            --warning: #C9710E;
            --warning-bg: rgba(201,113,14,.12);
            --danger: #c2412f;
            --danger-bg: rgba(194,65,47,.1);
            --info: #1E3A8C;
            --info-bg: rgba(30,58,140,.1);
            --glass: rgba(255,255,255,.7);
            --glass-border: #E2E7F0;
            --radius: 16px;
            --radius-sm: 12px;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Jost', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
        }
        .page { width: 100%; max-width: 1440px; margin: 0 auto; padding: 1rem; }

        /* ===== APP SHELL ===== */
        .app-shell { display: grid; gap: 1rem; grid-template-columns: 1fr; }

        .sidebar {
            background: linear-gradient(185deg,#234BC4 0%,#1E40AF 48%,#142C84 100%);
            border: 1px solid #16329B;
            border-radius: 20px;
            padding: 1.4rem 1rem;
            display: none;
            box-shadow: 0 18px 44px rgba(16,40,116,.28);
        }
        .sidebar.open { display: block; }
        .sidebar-brand { font-family: 'Playfair Display', serif; font-size: 1.6rem; font-weight: 700; margin-bottom: 1.2rem; padding: 0 .5rem;
            color: #fff; -webkit-text-fill-color: #fff;
        }
        .sidebar-logo { display:flex; align-items:center; gap:.6rem; margin-bottom:1.4rem; padding:0 .3rem; }
        .sidebar-logo img { width:42px; height:42px; border-radius:12px; object-fit:contain; background:#fff; padding:4px; box-shadow:0 4px 14px rgba(11,20,38,.25); }
        .nav { display: grid; gap: .35rem; }
        .nav-item {
            display: block; padding: .72rem .85rem; border-radius: var(--radius-sm);
            color: rgba(255,255,255,.74); text-decoration: none; font-weight: 600; font-size: .88rem;
            border: 1px solid transparent; transition: all .2s;
        }
        .nav-item:hover { background: rgba(255,255,255,.1); color: #fff; border-color: rgba(255,255,255,.16); }
        .nav-item.active { background: var(--accent); color: #1d1206; border-color: var(--accent); box-shadow: 0 8px 18px rgba(237,139,28,.42); }

        .hamburger {
            display: flex; align-items: center; gap: .5rem; width: 100%;
            background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius-sm);
            color: var(--text); padding: .65rem .9rem; font: inherit; font-size: .9rem; font-weight: 700; cursor: pointer;
            justify-content: center;
        }
        .hamburger-icon { display: flex; flex-direction: column; gap: 3px; }
        .hamburger-icon span { display: block; width: 20px; height: 2.5px; background: var(--muted); border-radius: 2px; }

        .main { min-width: 0; display: grid; gap: 1rem; align-content: start; }

        /* ===== HERO (bandeau navy — identité Lorny) ===== */
        .hero {
            position: relative; overflow: hidden;
            background: linear-gradient(120deg,#0B1426 0%,#16329B 68%,#1E40AF 100%);
            border: 1px solid #1E40AF; border-left: 5px solid var(--accent);
            border-radius: 18px; padding: clamp(1.3rem, 2.4vw, 1.9rem) clamp(1.3rem, 2.2vw, 2rem);
            display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;
            box-shadow: 0 14px 38px rgba(11,20,38,.22);
        }
        .hero::after { content:""; position:absolute; inset:0; z-index:0; pointer-events:none;
            background: url('{{ asset('image/banner-arch.svg') }}') right center / auto 230% no-repeat;
            opacity:.5; -webkit-mask-image:linear-gradient(90deg,transparent 30%,#000 100%); mask-image:linear-gradient(90deg,transparent 30%,#000 100%); }
        .hero-text { min-width: 0; position: relative; z-index: 1; }
        .hero h1 { font-family: 'Playfair Display', serif; font-size: clamp(1.6rem, 2.6vw, 2.1rem); font-weight: 700; color: #fff; letter-spacing: -.01em; line-height: 1.1; }
        .hero h1 em { font-style: italic; color: var(--accent); }
        .hero p { color: #B7C1D6; margin-top: .4rem; font-size: .9rem; max-width: 640px; }
        .logout-btn {
            position: relative; z-index: 1;
            background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.22);
            border-radius: 10px; padding: .55rem 1rem; color: #ECEFF5; font: inherit; font-size: .85rem; font-weight: 600;
            cursor: pointer; transition: all .2s; white-space: nowrap; backdrop-filter: blur(4px);
        }
        .logout-btn:hover { border-color: var(--accent); color: var(--accent); }

        /* ===== TABS ===== */
        .tabs {
            display: flex; gap: .4rem; overflow-x: auto; scrollbar-width: none; padding-bottom: .2rem;
        }
        .tabs::-webkit-scrollbar { display: none; }
        .tab-link {
            padding: .55rem .8rem; border-radius: var(--radius-sm); border: 1px solid var(--border);
            background: var(--surface); color: var(--muted); font-weight: 700; font-size: .82rem;
            text-decoration: none; white-space: nowrap; flex-shrink: 0; transition: all .2s; cursor: pointer;
        }
        .tab-link.active { background: rgba(237,139,28,.15); color: #ED8B1C; border-color: rgba(237,139,28,.3); }
        .tab-link:hover { color: var(--text); }

        /* ===== STATS ===== */
        .stats { display: grid; gap: .7rem; grid-template-columns: repeat(2, 1fr); }
        .stat {
            background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius);
            padding: 1rem; transition: transform .2s;
        }
        .stat:hover { transform: translateY(-2px); }
        .stat h2 { font-size: .72rem; color: var(--muted); text-transform: uppercase; letter-spacing: .06em; font-weight: 600; }
        .stat .val { font-size: 1.6rem; font-weight: 800; margin-top: .25rem; }
        .stat .val.green { color: var(--success); }
        .stat .val.blue { color: var(--info); }
        .stat .val.purple { color: var(--accent); }
        .stat .val.yellow { color: var(--warning); }
        .stat .val.red { color: var(--danger); }

        /* ===== PANEL ===== */
        .panel {
            background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius);
            padding: 1.2rem; box-shadow: 0 4px 18px rgba(30,58,140,.07);
        }
        .panel h3 { font-size: 1.1rem; font-weight: 700; margin-bottom: .3rem; }
        .panel .subtitle { color: var(--muted); font-size: .85rem; margin-bottom: 1rem; }

        /* ===== FORMS ===== */
        .form-grid { display: grid; gap: .8rem; grid-template-columns: 1fr; }
        label { display: block; font-size: .75rem; font-weight: 600; color: var(--muted); text-transform: uppercase; letter-spacing: .04em; margin-bottom: .35rem; }
        input, select, textarea {
            width: 100%; padding: .72rem .8rem; border: 1px solid var(--border); border-radius: var(--radius-sm);
            background: #F4F6FB; color: var(--text); font: inherit; font-size: .9rem; transition: border-color .2s;
        }
        input:focus, select:focus, textarea:focus { outline: none; border-color: var(--accent); box-shadow: 0 0 0 3px rgba(237,139,28,.15); }
        textarea { min-height: 90px; resize: vertical; }
        select { appearance: auto; }

        .btn {
            border: none; border-radius: var(--radius-sm); padding: .75rem 1.1rem; font: inherit; font-weight: 800;
            color: #1a1206; background: linear-gradient(135deg, #F4B25E, #ED8B1C 55%, #C9710E); cursor: pointer;
            transition: transform .15s, box-shadow .25s; margin-top: .3rem;
        }
        .btn:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(237,139,28,.45); }
        .btn-sm { padding: .45rem .7rem; font-size: .82rem; border-radius: 10px; }
        .btn-success { background: linear-gradient(135deg, #2e8b5f, #46b07c); color: #07140d; }
        .btn-outline {
            background: transparent; border: 1px solid var(--border); color: var(--muted); font-weight: 600;
        }
        .btn-outline:hover { border-color: var(--accent); color: var(--accent); background: rgba(237,139,28,.05); box-shadow: none; }

        /* ===== TABLE ===== */
        .table-wrap { border: 1px solid var(--border); border-radius: var(--radius-sm); overflow-x: auto; background: #F4F6FB; }
        .data-table { width: 100%; border-collapse: collapse; min-width: 580px; font-size: .84rem; }
        .data-table th, .data-table td { padding: .6rem .7rem; text-align: left; border-bottom: 1px solid var(--border); }
        .data-table th { background: rgba(51,65,85,.5); color: var(--muted); font-size: .73rem; text-transform: uppercase; letter-spacing: .04em; font-weight: 600; }
        .data-table tr:last-child td { border-bottom: none; }
        .data-table tr:hover { background: rgba(237,139,28,.04); }

        .badge {
            display: inline-flex; align-items: center; gap: .2rem; border-radius: 999px;
            padding: .22rem .55rem; font-size: .73rem; font-weight: 700; border: 1px solid transparent;
        }
        .badge-green { background: var(--success-bg); color: var(--success); border-color: rgba(52,211,153,.2); }
        .badge-yellow { background: var(--warning-bg); color: var(--warning); border-color: rgba(251,191,36,.2); }
        .badge-red { background: var(--danger-bg); color: var(--danger); border-color: rgba(248,113,113,.2); }
        .badge-blue { background: var(--info-bg); color: var(--info); border-color: rgba(46,139,95,.2); }
        .badge-purple { background: rgba(237,139,28,.1); color: var(--accent); border-color: rgba(237,139,28,.2); }

        .money { font-weight: 700; color: var(--success); }
        .empty-state {
            text-align: center; color: var(--muted); padding: 1.5rem .8rem; font-size: .9rem;
            border: 1px dashed var(--border); border-radius: var(--radius-sm); background: #F4F6FB;
        }

        .flash {
            background: var(--success-bg); border: 1px solid rgba(52,211,153,.25); color: var(--success);
            border-radius: var(--radius-sm); padding: .65rem .8rem; font-size: .88rem; margin-bottom: .8rem;
        }
        .errors {
            background: var(--danger-bg); border: 1px solid rgba(248,113,113,.25); color: var(--danger);
            border-radius: var(--radius-sm); padding: .65rem .8rem; font-size: .88rem; margin-bottom: .8rem;
        }
        .errors ul { margin: 0; padding-left: 1rem; }

        .sub-grid { display: grid; gap: .8rem; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); }

        .progress { width: 100%; height: 8px; border-radius: 999px; background: var(--surface2); overflow: hidden; }
        .progress-bar { height: 100%; border-radius: inherit; transition: width .35s ease; }
        .progress-bar.green { background: linear-gradient(90deg, #10b981, #34d399); }
        .progress-bar.purple { background: linear-gradient(90deg, #ED8B1C, #2e8b5f); }
        .progress-bar.yellow { background: linear-gradient(90deg, #ED8B1C, #F4B25E); }
        .progress-label { font-size: .75rem; color: var(--muted); margin-top: .2rem; }

        .uid-display {
            display: inline-block; background: rgba(237,139,28,.1); border: 1px solid rgba(237,139,28,.25);
            border-radius: 8px; padding: .2rem .5rem; font-family: monospace; font-size: .82rem; font-weight: 700;
            color: var(--accent); letter-spacing: .04em;
        }

        /* ===== ACTIONS ===== */
        .row-actions { display: flex; gap: .35rem; flex-wrap: nowrap; }
        .icon-btn {
            border: 1px solid var(--border); background: #F4F6FB; color: var(--muted);
            border-radius: 8px; padding: .3rem .55rem; font: inherit; font-size: .78rem; font-weight: 600;
            cursor: pointer; text-decoration: none; transition: all .15s; white-space: nowrap;
        }
        .icon-btn:hover { color: var(--text); border-color: var(--accent); }
        .icon-btn.danger:hover { color: var(--danger); border-color: rgba(248,113,113,.4); background: var(--danger-bg); }

        /* ===== MODAL ===== */
        .modal-overlay {
            display: none; position: fixed; inset: 0; z-index: 50;
            background: rgba(2,6,23,.7); backdrop-filter: blur(3px);
            align-items: flex-start; justify-content: center; padding: 1.5rem 1rem; overflow-y: auto;
        }
        .modal-overlay.open { display: flex; }
        .modal {
            background: var(--surface); border: 1px solid var(--border); border-radius: 20px;
            width: 100%; max-width: 620px; padding: 1.4rem; box-shadow: 0 30px 60px rgba(0,0,0,.5);
            margin: auto;
        }
        .modal-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; }
        .modal-head h3 { font-size: 1.1rem; font-weight: 700; }
        .modal-close {
            border: none; background: var(--surface2); color: var(--text); border-radius: 8px;
            width: 32px; height: 32px; font-size: 1.1rem; cursor: pointer; line-height: 1;
        }
        .modal-close:hover { background: var(--danger-bg); color: var(--danger); }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 479px) {
            .page { padding: .5rem; }
            .stats { grid-template-columns: 1fr 1fr; gap: .5rem; }
            .stat .val { font-size: 1.25rem; }
            .panel { padding: .8rem; }
            .sub-grid { grid-template-columns: 1fr; }
            .btn { width: 100%; text-align: center; }
        }
        @media (min-width: 480px) and (max-width: 719px) {
            .stats { grid-template-columns: repeat(3, 1fr); }
        }
        @media (min-width: 720px) {
            .stats { grid-template-columns: repeat(5, 1fr); }
            .form-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (min-width: 1024px) {
            .hamburger { display: none; }
            .sidebar { display: block; position: sticky; top: 1rem; min-height: calc(100vh - 2rem); }
            .app-shell { grid-template-columns: 260px minmax(0, 1fr); align-items: start; }
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="app-shell">
            <button class="hamburger" id="hamburgerBtn" type="button">
                <div class="hamburger-icon"><span></span><span></span><span></span></div>
                Menu
            </button>

            <aside class="sidebar" id="sidebarMenu">
                <div class="sidebar-logo">
                    <img src="{{ asset('image/lorny.png') }}" alt="Lorny Conseils Management">
                    <span class="sidebar-brand" style="margin-bottom:0">Lorny</span>
                </div>
                <nav class="nav">
                    <a class="nav-item active" data-tab-target="dashboard" href="#dashboard">📊 Tableau de bord</a>
                    <a class="nav-item" data-tab-target="biens" href="#biens">🏡 Biens (site)</a>
                    <a class="nav-item" data-tab-target="programmes" href="#programmes">🏗️ Programmes</a>
                    <a class="nav-item" data-tab-target="ilots" href="#ilots">🗺️ Îlots &amp; lots</a>
                    <a class="nav-item" data-tab-target="lots" href="#lots">🏠 Lots</a>
                    <a class="nav-item" data-tab-target="adhesions" href="#adhesions">📨 Demandes @if($demandes->count())<span style="background:var(--danger);color:#fff;border-radius:999px;padding:1px 7px;font-size:.7rem;margin-left:4px">{{ $demandes->count() }}</span>@endif</a>
                    <a class="nav-item" data-tab-target="souscripteurs" href="#souscripteurs">👥 Adhérents</a>
                    <a class="nav-item" data-tab-target="souscriptions" href="#souscriptions">📝 Adhésions</a>
                    <a class="nav-item" data-tab-target="versements" href="#versements">💰 Versements</a>
                    <a class="nav-item" data-tab-target="chantiers" href="#chantiers">🏗️ Avancement travaux</a>
                    <a class="nav-item" data-tab-target="bilan" href="#bilan">📈 Bilan Financier</a>
                    <a class="nav-item" data-tab-target="audit" href="#audit">🛡️ Journal d'audit</a>
                    <a class="nav-item" data-tab-target="parametres" href="#parametres">⚙️ Paramètres</a>
                </nav>
            </aside>

            <main class="main">
                {{-- HERO --}}
                <section class="hero">
                    <div class="hero-text">
                        <h1>Administration <em>Lorny</em></h1>
                        <p>Programmes, îlots, adhérents, versements, travaux et bilan financier.</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="logout-btn" type="submit">Déconnexion</button>
                    </form>
                </section>

                @if (session('success'))
                    <div class="flash">{{ session('success') }}</div>
                @endif
                @if ($errors->any())
                    <div class="errors"><ul>@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
                @endif

                {{-- ===== DASHBOARD ===== --}}
                <section class="stats" data-section="dashboard">
                    <article class="stat"><h2>Programmes</h2><div class="val purple">{{ $stats['programmes'] }}</div></article>
                    <article class="stat"><h2>Adhérents</h2><div class="val blue">{{ $stats['souscripteurs'] }}</div></article>
                    <article class="stat"><h2>Lots disponibles</h2><div class="val green">{{ $stats['lots_disponibles'] }}</div></article>
                    <article class="stat"><h2>Lots vendus</h2><div class="val yellow">{{ $stats['lots_vendus'] }}</div></article>
                    <article class="stat"><h2>Total encaissé</h2><div class="val green">{{ number_format($stats['total_encaisse'], 0, ',', ' ') }} <small style="font-size:.6em">FCFA</small></div></article>
                    <article class="stat"><h2>Montant en cours</h2><div class="val red">{{ number_format($stats['montant_en_cours'], 0, ',', ' ') }} <small style="font-size:.6em">FCFA</small></div></article>
                    <article class="stat"><h2>Frais d'ouverture encaissés</h2><div class="val green">{{ number_format($stats['frais_encaisses'], 0, ',', ' ') }} <small style="font-size:.6em">FCFA</small></div></article>
                </section>

                <div data-section="dashboard">
                    <div class="panel" style="margin-top: .5rem;">
                        <h3>Apercu par programme</h3>
                        <p class="subtitle">Taux de remplissage et encaissement par programme immobilier.</p>
                        <div class="table-wrap" style="margin-top: .8rem;">
                            <table class="data-table">
                                <thead>
                                    <tr><th>Programme</th><th>Localisation</th><th>Lots</th><th>Remplissage</th><th>Encaisse</th><th>Prevision</th></tr>
                                </thead>
                                <tbody>
                                    @forelse ($programmes as $prog)
                                        <tr>
                                            <td><strong>{{ $prog->name }}</strong></td>
                                            <td>{{ $prog->location }}</td>
                                            <td>{{ $prog->lots_count }} lots</td>
                                            <td>
                                                <div class="progress" style="width:100px">
                                                    <div class="progress-bar green" style="width:{{ $prog->tauxRemplissage() }}%"></div>
                                                </div>
                                                <div class="progress-label">{{ $prog->tauxRemplissage() }}%</div>
                                            </td>
                                            <td class="money">{{ number_format($prog->totalEncaisse(), 0, ',', ' ') }}</td>
                                            <td>{{ number_format($prog->totalPrevision(), 0, ',', ' ') }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="6" class="empty-state">Aucun programme cree.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- ===== BIENS (site public) ===== --}}
                <section data-section="biens" style="display:none;">
                    <div class="panel">
                        <h3>🏡 Publier un bien sur le site</h3>
                        <p class="subtitle">Ces biens apparaissent dans « Nos biens disponibles » sur le site public.</p>
                        <form method="POST" action="{{ route('biens.store') }}" enctype="multipart/form-data" class="form-grid" style="margin-top:.8rem;">
                            @csrf
                            <div><label>Nom du bien</label><input name="name" required placeholder="Ex: Villa Basse QUARTZ"></div>
                            <div><label>Type / description courte</label><input name="type" placeholder="Ex: 4 pièces · Fondation R+1"></div>
                            <div><label>Surface (m²)</label><input name="surface" type="number" step="0.01" min="0" placeholder="200"></div>
                            <div><label>Prix (FCFA)</label><input name="price" type="number" step="0.01" min="0" required placeholder="52000000"></div>
                            <div><label>Apport initial (%)</label><input name="apport_pct" type="number" min="0" max="100" value="35" required></div>
                            <div>
                                <label>Clôture</label>
                                <label style="display:flex;align-items:center;gap:.5rem;text-transform:none;color:var(--text);font-weight:500;margin-top:.4rem">
                                    <input type="checkbox" name="cloture_incluse" value="1" style="width:auto"> Livré avec clôture
                                </label>
                            </div>
                            <div><label>Prix clôture en option (FCFA)</label><input name="cloture_prix" type="number" step="0.01" min="0" value="5000000"></div>
                            <div>
                                <label>Statut</label>
                                <select name="status" required>
                                    <option value="disponible">Disponible</option>
                                    <option value="reserve">Réservé</option>
                                    <option value="vendu">Vendu</option>
                                </select>
                            </div>
                            <div><label>Ordre d'affichage</label><input name="ordre" type="number" min="0" value="0"></div>
                            <div style="grid-column:1/-1"><label>Description</label><textarea name="description" placeholder="Détails du bien..."></textarea></div>
                            <div style="grid-column:1/-1"><label>Photo</label><input name="photo" type="file" accept="image/*"></div>
                            <div><button class="btn" type="submit">Publier le bien</button></div>
                        </form>
                    </div>

                    <div class="panel" style="margin-top:1rem;">
                        <h3>Biens publiés</h3>
                        <div class="table-wrap" style="margin-top:.8rem;">
                            <table class="data-table">
                                <thead><tr><th>Nom</th><th>Type</th><th>Surface</th><th>Prix</th><th>Apport</th><th>Clôture</th><th>Statut</th><th>Actions</th></tr></thead>
                                <tbody>
                                    @forelse ($biens as $b)
                                        <tr>
                                            <td><strong>{{ $b->name }}</strong></td>
                                            <td style="font-size:.8rem;color:var(--muted)">{{ $b->type ?: '—' }}</td>
                                            <td>{{ $b->surface ? number_format($b->surface,0,',',' ').' m²' : '—' }}</td>
                                            <td class="money">{{ number_format((float)$b->price,0,',',' ') }} F</td>
                                            <td>{{ $b->apport_pct }}% · {{ number_format($b->apportInitial(),0,',',' ') }} F</td>
                                            <td>
                                                @if($b->cloture_incluse)<span class="badge badge-green">Incluse</span>
                                                @else<span class="badge badge-yellow">+{{ number_format((float)$b->cloture_prix,0,',',' ') }} F</span>@endif
                                            </td>
                                            <td>
                                                @if($b->status==='disponible')<span class="badge badge-green">Disponible</span>
                                                @elseif($b->status==='reserve')<span class="badge badge-yellow">Réservé</span>
                                                @else<span class="badge badge-red">Vendu</span>@endif
                                            </td>
                                            <td>
                                                <div class="row-actions">
                                                    <button type="button" class="icon-btn"
                                                        data-edit="bien" data-id="{{ $b->id }}"
                                                        data-name="{{ $b->name }}" data-type="{{ $b->type }}"
                                                        data-surface="{{ $b->surface ? (float)$b->surface : '' }}"
                                                        data-price="{{ (float)$b->price }}" data-apport_pct="{{ $b->apport_pct }}"
                                                        data-cloture_incluse="{{ $b->cloture_incluse ? 1 : 0 }}"
                                                        data-cloture_prix="{{ (float)$b->cloture_prix }}"
                                                        data-status="{{ $b->status }}" data-ordre="{{ $b->ordre }}"
                                                        data-description="{{ $b->description }}">Modifier</button>
                                                    <form method="POST" action="{{ route('biens.destroy', $b) }}" onsubmit="return confirm('Supprimer ce bien ?');">@csrf<button type="submit" class="icon-btn danger">Supprimer</button></form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="8" class="empty-state">Aucun bien publié.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>

                {{-- ===== PROGRAMMES ===== --}}
                <section data-section="programmes" style="display:none;">
                    <div class="panel">
                        <h3>🏗️ Creer un programme</h3>
                        <p class="subtitle">Ajoutez un nouveau programme immobilier.</p>
                        <form method="POST" action="{{ route('programmes.store') }}" class="form-grid" style="margin-top: .8rem;">
                            @csrf
                            <div><label>Nom du programme</label><input name="name" value="{{ old('name') }}" required placeholder="Ex: Residence Les Palmiers"></div>
                            <div><label>Localisation</label><input name="location" value="{{ old('location') }}" required placeholder="Ex: Abidjan, Cocody"></div>
                            <div>
                                <label>Statut</label>
                                <select name="status">
                                    <option value="actif">Actif</option>
                                    <option value="suspendu">Suspendu</option>
                                    <option value="termine">Termine</option>
                                </select>
                            </div>
                            <div><label>Surface utile (m²)</label><input name="surface_utile" type="number" step="0.01" min="0" value="{{ old('surface_utile') }}" placeholder="Ex: 85"></div>
                            <div><label>Surface totale (m²)</label><input name="surface_totale" type="number" step="0.01" min="0" value="{{ old('surface_totale') }}" placeholder="Ex: 110"></div>
                            <div style="grid-column: 1/-1;"><label>Description</label><textarea name="description" placeholder="Description du programme...">{{ old('description') }}</textarea></div>
                            <div><button class="btn" type="submit">Creer le programme</button></div>
                        </form>
                    </div>

                    <div class="panel" style="margin-top: 1rem;">
                        <h3>Liste des programmes</h3>
                        <div class="table-wrap" style="margin-top: .8rem;">
                            <table class="data-table">
                                <thead><tr><th>Nom</th><th>Localisation</th><th>Lots</th><th>Statut</th><th>Remplissage</th><th>Actions</th></tr></thead>
                                <tbody>
                                    @forelse ($programmes as $prog)
                                        <tr>
                                            <td><strong>{{ $prog->name }}</strong></td>
                                            <td>{{ $prog->location }}</td>
                                            <td>{{ $prog->lots_count }}</td>
                                            <td>
                                                @if ($prog->status === 'actif') <span class="badge badge-green">Actif</span>
                                                @elseif ($prog->status === 'suspendu') <span class="badge badge-yellow">Suspendu</span>
                                                @else <span class="badge badge-red">Termine</span>
                                                @endif
                                            </td>
                                            <td>{{ $prog->tauxRemplissage() }}%</td>
                                            <td>
                                                <div class="row-actions">
                                                    <button type="button" class="icon-btn"
                                                        data-edit="programme"
                                                        data-id="{{ $prog->id }}"
                                                        data-name="{{ $prog->name }}"
                                                        data-location="{{ $prog->location }}"
                                                        data-status="{{ $prog->status }}"
                                                        data-surface_utile="{{ $prog->surface_utile ? (float) $prog->surface_utile : '' }}"
                                                        data-surface_totale="{{ $prog->surface_totale ? (float) $prog->surface_totale : '' }}"
                                                        data-description="{{ $prog->description }}">Modifier</button>
                                                    <form method="POST" action="{{ route('programmes.destroy', $prog) }}" onsubmit="return confirm('Supprimer ce programme et tous ses lots ?');">
                                                        @csrf
                                                        <button type="submit" class="icon-btn danger">Supprimer</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="6" class="empty-state">Aucun programme.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>

                {{-- ===== ÎLOTS & LOTS ===== --}}
                <section data-section="ilots" style="display:none;">
                    <div class="panel">
                        <h3>🗺️ Créer un îlot</h3>
                        <p class="subtitle">Définissez un îlot et son nombre de lots : ils seront générés automatiquement.</p>
                        <form method="POST" action="{{ route('ilots.store') }}" class="form-grid" style="margin-top:.8rem;">
                            @csrf
                            <div>
                                <label>Programme</label>
                                <select name="programme_id" required>
                                    <option value="">Sélectionner...</option>
                                    @foreach ($programmes as $prog)
                                        <option value="{{ $prog->id }}">{{ $prog->name }} ({{ $prog->location }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div><label>Nom de l'îlot</label><input name="name" required placeholder="Ex: A, B, Îlot Nord"></div>
                            <div><label>Nombre de lots</label><input name="nb_lots" type="number" min="1" max="500" value="10" required></div>
                            <div>
                                <label>Type de lot</label>
                                <select name="type_logement" required>
                                    <option value="terrain">Terrain</option>
                                    <option value="villa">Villa</option>
                                    <option value="duplex">Duplex</option>
                                    <option value="studio">Studio</option>
                                    <option value="F2">F2</option>
                                    <option value="F3">F3</option>
                                    <option value="F4">F4</option>
                                </select>
                            </div>
                            <div><label>Prix unitaire (FCFA)</label><input name="price" type="number" step="0.01" min="0" value="0" required></div>
                            <div><label>Surface (m²)</label><input name="surface" type="number" step="0.01" min="0"></div>
                            <div><button class="btn" type="submit">Créer l'îlot</button></div>
                        </form>
                    </div>

                    {{-- Légende --}}
                    <div class="panel" style="margin-top:1rem;">
                        <div style="display:flex; gap:1.2rem; flex-wrap:wrap; align-items:center; font-size:.84rem;">
                            <strong style="font-size:.9rem">Plan des lots</strong>
                            <span style="display:flex;align-items:center;gap:.4rem"><span style="width:14px;height:14px;border-radius:4px;background:#e03b3b;display:inline-block"></span> Disponible</span>
                            <span style="display:flex;align-items:center;gap:.4rem"><span style="width:14px;height:14px;border-radius:4px;background:#ED8B1C;display:inline-block"></span> Réservé / versement en cours</span>
                            <span style="display:flex;align-items:center;gap:.4rem"><span style="width:14px;height:14px;border-radius:4px;background:#1f8a5a;display:inline-block"></span> Soldé (attestation disponible)</span>
                        </div>
                    </div>

                    {{-- Plan par programme / îlot --}}
                    @forelse ($programmes as $prog)
                        @if ($prog->ilots->count() > 0)
                            <div class="panel" style="margin-top:1rem;">
                                <h3>{{ $prog->name }} <span style="color:var(--muted);font-weight:500;font-size:.85rem">— {{ $prog->location }}</span></h3>
                                @foreach ($prog->ilots as $ilot)
                                    <div style="margin-top:1rem;">
                                        <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:.5rem;margin-bottom:.6rem;">
                                            <strong style="font-size:.95rem">Îlot {{ $ilot->name }} <span style="color:var(--muted);font-weight:500">· {{ $ilot->lots->count() }} lots</span></strong>
                                            <form method="POST" action="{{ route('ilots.destroy', $ilot) }}" onsubmit="return confirm('Supprimer cet îlot et ses lots disponibles ?');">
                                                @csrf
                                                <button type="submit" class="icon-btn danger">Supprimer l'îlot</button>
                                            </form>
                                        </div>
                                        <div style="display:flex; flex-wrap:wrap; gap:.5rem;">
                                            @foreach ($ilot->lots as $lot)
                                                @php
                                                    $c = ['red'=>'#e03b3b','orange'=>'#ED8B1C','green'=>'#1f8a5a'][$lot->statusColor()];
                                                    $sousc = $lot->souscription;
                                                @endphp
                                                @if ($lot->status === 'vendu' && $sousc)
                                                    <a href="{{ route('pdf.attestation', $sousc) }}" target="_blank"
                                                       title="{{ $lot->reference }} — Soldé · Cliquer pour l'attestation"
                                                       style="width:54px;height:54px;border-radius:10px;background:{{ $c }};color:#fff;display:flex;flex-direction:column;align-items:center;justify-content:center;text-decoration:none;font-size:.7rem;font-weight:700;box-shadow:0 2px 6px rgba(0,0,0,.12)">
                                                        {{ $lot->reference }}
                                                        <span style="font-size:.6rem;font-weight:500;">📄</span>
                                                    </a>
                                                @else
                                                    <span title="{{ $lot->reference }} — {{ $lot->statusLabel() }}"
                                                          style="width:54px;height:54px;border-radius:10px;background:{{ $c }};color:#fff;display:flex;align-items:center;justify-content:center;font-size:.72rem;font-weight:700;box-shadow:0 2px 6px rgba(0,0,0,.12)">
                                                        {{ $lot->reference }}
                                                    </span>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @empty
                    @endforelse

                    @if ($programmes->sum(fn($p) => $p->ilots->count()) === 0)
                        <div class="panel" style="margin-top:1rem;"><div class="empty-state">Aucun îlot créé. Utilisez le formulaire ci-dessus.</div></div>
                    @endif
                </section>

                {{-- ===== LOTS ===== --}}
                <section data-section="lots" style="display:none;">
                    <div class="panel">
                        <h3>🏠 Creer un lot</h3>
                        <p class="subtitle">Ajoutez un lot a un programme immobilier existant.</p>
                        <form method="POST" action="{{ route('lots.store') }}" class="form-grid" style="margin-top: .8rem;">
                            @csrf
                            <div>
                                <label>Programme</label>
                                <select name="programme_id" required>
                                    <option value="">Selectionner...</option>
                                    @foreach ($programmes as $prog)
                                        <option value="{{ $prog->id }}">{{ $prog->name }} ({{ $prog->location }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div><label>Reference du lot</label><input name="reference" value="{{ old('reference') }}" required placeholder="Ex: A-01, B-12"></div>
                            <div>
                                <label>Type de logement</label>
                                <select name="type_logement" required>
                                    <option value="studio">Studio</option>
                                    <option value="F2">F2</option>
                                    <option value="F3">F3</option>
                                    <option value="F4">F4</option>
                                    <option value="villa">Villa</option>
                                    <option value="duplex">Duplex</option>
                                    <option value="terrain">Terrain</option>
                                </select>
                            </div>
                            <div><label>Prix (FCFA)</label><input name="price" type="number" step="0.01" min="0" value="{{ old('price') }}" required></div>
                            <div><label>Surface (m²)</label><input name="surface" type="number" step="0.01" min="0" value="{{ old('surface') }}"></div>
                            <div style="grid-column:1/-1"><label>Description</label><textarea name="description" placeholder="Details du lot...">{{ old('description') }}</textarea></div>
                            <div><button class="btn" type="submit">Creer le lot</button></div>
                        </form>
                    </div>

                    <div class="panel" style="margin-top: 1rem;">
                        <h3>Liste des lots</h3>
                        <div class="table-wrap" style="margin-top: .8rem;">
                            <table class="data-table">
                                <thead><tr><th>Ref</th><th>Programme</th><th>Type</th><th>Surface</th><th>Prix</th><th>Statut</th><th>Actions</th></tr></thead>
                                <tbody>
                                    @forelse ($lots as $lot)
                                        <tr>
                                            <td><strong>{{ $lot->reference }}</strong></td>
                                            <td>{{ $lot->programme->name }}</td>
                                            <td><span class="badge badge-purple">{{ $lot->type_logement }}</span></td>
                                            <td>{{ $lot->surface ? $lot->surface . ' m²' : '-' }}</td>
                                            <td class="money">{{ number_format((float) $lot->price, 0, ',', ' ') }} FCFA</td>
                                            <td>
                                                @if ($lot->status === 'disponible') <span class="badge badge-green">Disponible</span>
                                                @elseif ($lot->status === 'reserve') <span class="badge badge-yellow">Reserve</span>
                                                @else <span class="badge badge-red">Vendu</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="row-actions">
                                                    <button type="button" class="icon-btn"
                                                        data-edit="lot"
                                                        data-id="{{ $lot->id }}"
                                                        data-reference="{{ $lot->reference }}"
                                                        data-type_logement="{{ $lot->type_logement }}"
                                                        data-price="{{ $lot->price }}"
                                                        data-surface="{{ $lot->surface }}"
                                                        data-status="{{ $lot->status }}"
                                                        data-description="{{ $lot->description }}">Modifier</button>
                                                    <form method="POST" action="{{ route('lots.destroy', $lot) }}" onsubmit="return confirm('Supprimer ce lot ?');">
                                                        @csrf
                                                        <button type="submit" class="icon-btn danger">Supprimer</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="7" class="empty-state">Aucun lot enregistre.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>

                {{-- ===== DEMANDES D'ADHÉSION ===== --}}
                <section data-section="adhesions" style="display:none;">
                    <div class="panel">
                        <h3>📨 Demandes d'adhésion en attente</h3>
                        <p class="subtitle">Comptes créés depuis le site public, à valider pour activer l'accès membre.</p>
                        <div class="table-wrap" style="margin-top:.8rem;">
                            <table class="data-table">
                                <thead><tr><th>Date</th><th>Nom complet</th><th>Email</th><th>Téléphone</th><th>Naissance</th><th>Actions</th></tr></thead>
                                <tbody>
                                    @forelse($demandes as $d)
                                        <tr>
                                            <td>{{ $d->created_at->format('d/m/Y') }}</td>
                                            <td><strong>{{ $d->fullName() }}</strong><div style="font-size:.72rem;color:var(--muted)">{{ $d->uid }}</div></td>
                                            <td>{{ $d->email }}</td>
                                            <td>{{ $d->phone ?: '—' }}</td>
                                            <td>{{ $d->date_naissance?->format('d/m/Y') ?: '—' }}</td>
                                            <td>
                                                <div class="row-actions">
                                                    <form method="POST" action="{{ route('adherents.validate', $d) }}">@csrf<button type="submit" class="icon-btn" style="border-color:rgba(31,138,90,.4);color:var(--success)">✓ Valider</button></form>
                                                    <form method="POST" action="{{ route('adherents.reject', $d) }}" onsubmit="return confirm('Refuser cette demande ?');">@csrf<button type="submit" class="icon-btn danger">Refuser</button></form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="6" class="empty-state">Aucune demande en attente.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>

                {{-- ===== SOUSCRIPTEURS ===== --}}
                <section data-section="souscripteurs" style="display:none;">
                    <div class="panel">
                        <h3>👥 Inscrire un adhérent</h3>
                        <p class="subtitle">Le systeme generera automatiquement un identifiant unique (ex: IMM-2026-X89Z).</p>
                        <form method="POST" action="{{ route('souscripteurs.store') }}" class="form-grid" style="margin-top: .8rem;">
                            @csrf
                            <div><label>Prenom</label><input name="first_name" value="{{ old('first_name') }}" required></div>
                            <div><label>Nom</label><input name="last_name" value="{{ old('last_name') }}" required></div>
                            <div><label>Email</label><input name="email" type="email" value="{{ old('email') }}" placeholder="email@exemple.com"></div>
                            <div><label>Telephone</label><input name="phone" value="{{ old('phone') }}" placeholder="+225 XX XX XX XX XX"></div>
                            <div><label>Date de naissance</label><input name="date_naissance" type="date" value="{{ old('date_naissance') }}"></div>
                            <div>
                                <label>Type de piece d'identite</label>
                                <select name="id_type">
                                    <option value="">Aucune</option>
                                    <option value="CNI">CNI</option>
                                    <option value="Passeport">Passeport</option>
                                    <option value="Attestation">Attestation</option>
                                    <option value="Permis">Permis de conduire</option>
                                </select>
                            </div>
                            <div><label>Numero de piece</label><input name="id_number" value="{{ old('id_number') }}"></div>
                            <div style="grid-column:1/-1"><label>Adresse</label><textarea name="address" placeholder="Adresse du souscripteur..." style="min-height:60px">{{ old('address') }}</textarea></div>
                            <div><button class="btn" type="submit">Inscrire l'adhérent</button></div>
                        </form>
                    </div>

                    <div class="panel" style="margin-top: 1rem;">
                        <div style="display:flex;justify-content:space-between;align-items:center;gap:1rem;flex-wrap:wrap;">
                            <h3>Liste des adhérents <span style="color:var(--muted);font-weight:600;font-size:.82rem">({{ $souscripteurs->count() }})</span></h3>
                            <input id="adherentSearch" type="search" autocomplete="off" placeholder="Rechercher : nom, ID, email, téléphone…" style="max-width:340px;width:100%;padding:.55rem .85rem;border:1px solid var(--border);border-radius:var(--radius-sm);background:var(--surface);color:var(--text);font:inherit;font-size:.85rem;">
                        </div>
                        <div id="adherentEmpty" class="empty-state" style="display:none;">Aucun adhérent ne correspond à la recherche.</div>
                        <div class="table-wrap" style="margin-top: .8rem;">
                            <table class="data-table" id="adherentsTable">
                                <thead><tr><th>ID Unique</th><th>Nom complet</th><th>Email</th><th>Telephone</th><th>Piece</th><th>Adhésions</th><th>Actions</th></tr></thead>
                                <tbody>
                                    @forelse ($souscripteurs as $s)
                                        <tr>
                                            <td><span class="uid-display">{{ $s->uid }}</span></td>
                                            <td>
                                                <strong>{{ $s->fullName() }}</strong>
                                                <div style="margin-top:.2rem">
                                                    @if ($s->frais_ouverture_payes)
                                                        <span class="badge badge-green" style="font-size:.66rem">Frais payés</span>
                                                        <a href="{{ route('pdf.frais', $s) }}" target="_blank" style="color:var(--accent);font-size:.7rem;font-weight:600;text-decoration:none;margin-left:.3rem">Reçu PDF</a>
                                                    @else
                                                        <span class="badge badge-red" style="font-size:.66rem">Frais dus : {{ number_format((float) $s->frais_ouverture, 0, ',', ' ') }} F</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>{{ $s->email ?: '-' }}</td>
                                            <td>{{ $s->phone ?: '-' }}</td>
                                            <td>{{ $s->id_type ? $s->id_type . ' - ' . $s->id_number : '-' }}</td>
                                            <td><span class="badge badge-blue">{{ $s->souscriptions->count() }}</span></td>
                                            <td>
                                                <div class="row-actions">
                                                    <button type="button" class="icon-btn"
                                                        data-edit="souscripteur"
                                                        data-id="{{ $s->id }}"
                                                        data-first_name="{{ $s->first_name }}"
                                                        data-last_name="{{ $s->last_name }}"
                                                        data-email="{{ $s->email }}"
                                                        data-phone="{{ $s->phone }}"
                                                        data-date_naissance="{{ $s->date_naissance?->format('Y-m-d') }}"
                                                        data-id_type="{{ $s->id_type }}"
                                                        data-id_number="{{ $s->id_number }}"
                                                        data-address="{{ $s->address }}"
                                                        data-app_access="{{ $s->app_access ? 1 : 0 }}"
                                                        data-frais_ouverture="{{ (float) $s->frais_ouverture }}"
                                                        data-frais_ouverture_payes="{{ $s->frais_ouverture_payes ? 1 : 0 }}">Modifier</button>
                                                    <a href="{{ route('consultation.direct', $s->uid) }}" target="_blank" class="icon-btn">Suivi</a>
                                                    <form method="POST" action="{{ route('souscripteurs.destroy', $s) }}" onsubmit="return confirm('Supprimer ce souscripteur ?');">
                                                        @csrf
                                                        <button type="submit" class="icon-btn danger">Supprimer</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="7" class="empty-state">Aucun adhérent inscrit.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <script>
                    (function(){
                        var q = document.getElementById('adherentSearch');
                        var tbl = document.getElementById('adherentsTable');
                        var empty = document.getElementById('adherentEmpty');
                        if (!q || !tbl) return;
                        q.addEventListener('input', function () {
                            var t = this.value.trim().toLowerCase();
                            var rows = tbl.tBodies[0] ? tbl.tBodies[0].rows : [];
                            var shown = 0;
                            for (var i = 0; i < rows.length; i++) {
                                var match = rows[i].textContent.toLowerCase().indexOf(t) !== -1;
                                rows[i].style.display = match ? '' : 'none';
                                if (match) shown++;
                            }
                            if (empty) empty.style.display = (shown === 0) ? 'block' : 'none';
                        });
                    })();
                    </script>
                </section>

                {{-- ===== SOUSCRIPTIONS ===== --}}
                <section data-section="souscriptions">
                    <div class="panel">
                        <h3>📝 Créer une adhésion</h3>
                        <p class="subtitle">Affecter un adhérent à un programme et un lot.</p>
                        <form method="POST" action="{{ route('souscriptions.store') }}" class="form-grid" style="margin-top: .8rem;" id="souscriptionForm">
                            @csrf
                            <div>
                                <label>Adhérent</label>
                                <select name="souscripteur_id" required>
                                    <option value="">Selectionner...</option>
                                    @foreach ($souscripteurs as $s)
                                        <option value="{{ $s->id }}">{{ $s->uid }} — {{ $s->fullName() }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label>Programme</label>
                                <select name="programme_id" id="sousc_programme" required>
                                    <option value="">Selectionner...</option>
                                    @foreach ($programmes as $prog)
                                        <option value="{{ $prog->id }}">{{ $prog->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label>Lot</label>
                                <select name="lot_id" id="sousc_lot" required>
                                    <option value="">Selectionner un programme d'abord...</option>
                                    @foreach ($lots->where('status', 'disponible') as $lot)
                                        <option value="{{ $lot->id }}" data-programme="{{ $lot->programme_id }}" data-price="{{ $lot->price }}">
                                            {{ $lot->reference }} — {{ $lot->type_logement }} ({{ number_format((float) $lot->price, 0, ',', ' ') }} FCFA)
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div><label>Prix total du bien (FCFA)</label><input name="total_price" id="sousc_price" type="number" step="0.01" min="0" value="{{ old('total_price') }}" required></div>
                            <div><label>Apport initial (FCFA)</label><input name="apport_initial" id="sousc_apport" type="number" step="0.01" min="0" value="{{ old('apport_initial', 0) }}">
                                <small id="sousc_apport_hint" style="display:block;margin-top:.4rem;font-size:.74rem;font-weight:600;line-height:1.45"></small>
                            </div>
                            <div>
                                <label>Rythme de règlement</label>
                                <select name="rythme" id="sousc_rythme" required>
                                    @foreach (\App\Models\Souscription::RYTHMES as $val => $lib)
                                        <option value="{{ $val }}" @selected(old('rythme')===$val)>{{ $lib }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div><label>Durée (mois) <span style="text-transform:none;font-weight:400">— optionnel</span></label><input id="sousc_duree" type="number" min="1" placeholder="Ex: 24" oninput=""></div>
                            <div><label>Nombre d'échéances</label><input name="nb_mensualites" id="sousc_nb" type="number" min="1" max="360" value="{{ old('nb_mensualites', 12) }}" required></div>
                            <div><label>Date de souscription</label><input name="date_souscription" type="date" value="{{ old('date_souscription', now()->format('Y-m-d')) }}" required></div>
                            <div style="grid-column:1/-1">
                                <div id="sousc_lock" style="display:none; background:var(--warning-bg); border:1px solid rgba(201,113,14,.3); border-radius:var(--radius-sm); padding:.7rem 1rem; font-size:.84rem; margin-bottom:.5rem;">
                                    🔒 <strong>Apport de <span id="sousc_pct">35</span> % non atteint</strong> (minimum <span id="sousc_requis"></span>). Les échéances ne démarreront qu'une fois ce seuil atteint — il reste <strong id="sousc_reste_apport"></strong>.
                                </div>
                                <div id="sousc_echeancier" style="display:none; background:var(--success-bg); border:1px solid rgba(31,138,90,.25); border-radius:var(--radius-sm); padding:.7rem 1rem; font-size:.88rem;">
                                    Montant par échéance : <strong id="sousc_montant_ech" style="color:var(--accent)"></strong>
                                    <span style="color:var(--muted)"> (reste de <strong id="sousc_reste_ech"></strong> réparti sur <strong id="sousc_nb_ech"></strong> échéances)</span>
                                </div>
                            </div>
                            <div style="grid-column:1/-1"><label>Notes</label><textarea name="notes" placeholder="Notes complementaires...">{{ old('notes') }}</textarea></div>
                            <div><button class="btn" type="submit">Créer l'adhésion</button></div>
                        </form>
                    </div>

                    <div class="panel" style="margin-top: 1rem;">
                        <h3>Liste des adhésions</h3>
                        <div class="table-wrap" style="margin-top: .8rem;">
                            <table class="data-table">
                                <thead><tr><th>Adhérent</th><th>Programme</th><th>Lot</th><th>Prix total</th><th>Verse</th><th>Reste</th><th>Progression</th><th>Statut</th><th>Actions</th></tr></thead>
                                <tbody>
                                    @forelse ($souscriptions as $sousc)
                                        @php
                                            $verse = $sousc->totalVerse();
                                            $reste = $sousc->resteAPayer();
                                            $pct = $sousc->progressPercent();
                                        @endphp
                                        <tr>
                                            <td>
                                                <strong>{{ $sousc->souscripteur->fullName() }}</strong>
                                                <div style="font-size:.75rem;color:var(--muted)">{{ $sousc->souscripteur->uid }}</div>
                                            </td>
                                            <td>
                                                {{ $sousc->programme->name }}
                                                <div style="font-size:.72rem;color:var(--muted)">{{ $sousc->rythmeLabel() }} · {{ number_format((float) $sousc->mensualite, 0, ',', ' ') }} F/éch.</div>
                                            </td>
                                            <td><span class="badge badge-purple">{{ $sousc->lot->reference }}</span></td>
                                            <td>{{ number_format((float) $sousc->total_price, 0, ',', ' ') }}</td>
                                            <td class="money">{{ number_format($verse, 0, ',', ' ') }}</td>
                                            <td style="color: {{ $reste > 0 ? 'var(--danger)' : 'var(--success)' }}; font-weight:700;">{{ number_format($reste, 0, ',', ' ') }}</td>
                                            <td>
                                                <div class="progress" style="width:100px">
                                                    <div class="progress-bar {{ $pct >= 100 ? 'green' : ($pct >= 50 ? 'purple' : 'yellow') }}" style="width:{{ $pct }}%"></div>
                                                </div>
                                                <div class="progress-label">{{ $pct }}%</div>
                                            </td>
                                            <td>
                                                @if ($sousc->status === 'solde') <span class="badge badge-green">✓ Solde</span>
                                                @elseif ($sousc->status === 'annule') <span class="badge badge-red">Annule</span>
                                                @else <span class="badge badge-yellow">En cours</span>
                                                @endif
                                                @if ($sousc->status === 'en_cours' && ! $sousc->echeancesDebloquees())
                                                    <div style="margin-top:.4rem">
                                                        <span class="badge badge-red" title="Apport minimum non atteint — échéancier verrouillé">🔒 Apport {{ $sousc->apportRequisPct() }}%</span>
                                                        <div style="font-size:.7rem;color:var(--muted);margin-top:.25rem">reste {{ number_format($sousc->resteApport(), 0, ',', ' ') }} F</div>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="row-actions">
                                                    <a href="{{ route('pdf.attestation', $sousc) }}" target="_blank" class="icon-btn">Attestation</a>
                                                    <button type="button" class="icon-btn"
                                                        data-edit="souscription"
                                                        data-id="{{ $sousc->id }}"
                                                        data-total_price="{{ $sousc->total_price }}"
                                                        data-nb_mensualites="{{ $sousc->nb_mensualites }}"
                                                        data-rythme="{{ $sousc->rythme }}"
                                                        data-date_souscription="{{ $sousc->date_souscription->format('Y-m-d') }}"
                                                        data-status="{{ $sousc->status }}"
                                                        data-notes="{{ $sousc->notes }}">Modifier</button>
                                                    <form method="POST" action="{{ route('souscriptions.destroy', $sousc) }}" onsubmit="return confirm('Supprimer cette souscription ? Le lot sera libéré et les versements supprimés.');">
                                                        @csrf
                                                        <button type="submit" class="icon-btn danger">Supprimer</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="9" class="empty-state">Aucune adhésion.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>

                {{-- ===== VERSEMENTS ===== --}}
                <section data-section="versements">
                    <div class="panel">
                        <h3>💰 Enregistrer un versement</h3>
                        <p class="subtitle">Sélectionnez d'abord le client pour voir son reste à payer, puis enregistrez le versement.</p>
                        <form method="POST" action="{{ route('versements.store') }}" enctype="multipart/form-data" class="form-grid" style="margin-top: .8rem;">
                            @csrf
                            <div>
                                <label>1. Client</label>
                                <select id="vers_client" required>
                                    <option value="">Sélectionner un client...</option>
                                    @foreach ($souscriptions->where('status', 'en_cours')->groupBy('souscripteur_id') as $group)
                                        @php $sc = $group->first()->souscripteur; @endphp
                                        <option value="{{ $sc->id }}">{{ $sc->fullName() }} — {{ $sc->uid }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label>2. Dossier / lot</label>
                                <select name="souscription_id" id="vers_souscription" required>
                                    <option value="">Sélectionner un client d'abord...</option>
                                    @foreach ($souscriptions->where('status', 'en_cours') as $sousc)
                                        <option value="{{ $sousc->id }}"
                                            data-client="{{ $sousc->souscripteur_id }}"
                                            data-reste="{{ $sousc->resteAPayer() }}"
                                            data-total="{{ (float) $sousc->total_price }}"
                                            data-verse="{{ $sousc->totalVerse() }}">
                                            {{ $sousc->programme->name }} — Lot {{ $sousc->lot->reference }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div style="grid-column:1/-1">
                                <div id="vers_resume" style="display:none; background:var(--success-bg); border:1px solid rgba(31,138,90,.25); border-radius:var(--radius-sm); padding:.8rem 1rem; font-size:.9rem;">
                                    <div style="display:flex; justify-content:space-between; gap:1rem; flex-wrap:wrap;">
                                        <span style="color:var(--muted)">Déjà versé : <strong id="vers_verse" style="color:var(--success)"></strong></span>
                                        <span style="color:var(--muted)">Prix total : <strong id="vers_total" style="color:var(--text)"></strong></span>
                                        <span style="color:var(--muted)">Reste à payer : <strong id="vers_reste" style="color:var(--danger); font-size:1.05em"></strong></span>
                                    </div>
                                </div>
                            </div>

                            <div><label>3. Montant du versement (FCFA)</label><input name="amount" id="vers_amount" type="number" step="0.01" min="1" value="{{ old('amount') }}" required></div>
                            <div><label>Date de paiement</label><input name="payment_date" type="date" value="{{ old('payment_date', now()->format('Y-m-d')) }}" required></div>
                            <div>
                                <label>Mode de paiement</label>
                                <select name="payment_method" required>
                                    <option value="especes">Espèces</option>
                                    <option value="cheque">Chèque</option>
                                    <option value="virement">Virement bancaire</option>
                                    <option value="mobile_money">Mobile Money</option>
                                </select>
                            </div>
                            <div><label>Référence</label><input name="reference" value="{{ old('reference') }}" placeholder="Ex: CHQ-2026-001"></div>
                            <div><label>Reçu (image ou PDF — visible par le client)</label><input name="recu" type="file" accept="image/*,application/pdf"></div>
                            <div style="grid-column:1/-1"><label>Note</label><textarea name="note" placeholder="Commentaire..." style="min-height:60px">{{ old('note') }}</textarea></div>
                            <div><button class="btn" type="submit">Enregistrer le versement</button></div>
                        </form>
                    </div>

                    <div class="panel" style="margin-top: 1rem;">
                        <h3>Historique des versements</h3>
                        <div class="table-wrap" style="margin-top: .8rem;">
                            <table class="data-table">
                                <thead><tr><th>Date</th><th>Adhérent</th><th>Montant</th><th>Mode</th><th>Reference</th><th>Actions</th></tr></thead>
                                <tbody>
                                    @forelse ($versements as $v)
                                        <tr>
                                            <td>{{ $v->payment_date->format('d/m/Y') }}</td>
                                            <td>{{ $v->souscription->souscripteur->fullName() }}</td>
                                            <td class="money">{{ number_format((float) $v->amount, 0, ',', ' ') }} FCFA</td>
                                            <td><span class="badge badge-blue">{{ ucfirst(str_replace('_', ' ', $v->payment_method)) }}</span></td>
                                            <td>{{ $v->reference ?: '-' }}</td>
                                            <td>
                                                <div class="row-actions">
                                                    <a href="{{ route('pdf.facture', $v) }}" target="_blank" class="icon-btn">Facture</a>
                                                    @if ($v->recu)
                                                        <a href="{{ route('pdf.versement.recu', $v) }}" target="_blank" class="icon-btn">Voir reçu</a>
                                                    @endif
                                                    <form method="POST" action="{{ route('versements.recu', $v) }}" enctype="multipart/form-data" style="display:inline-flex;gap:.3rem;align-items:center">
                                                        @csrf
                                                        <input type="file" name="recu" accept="image/*,application/pdf" required style="max-width:150px;font-size:.78rem">
                                                        <button type="submit" class="icon-btn">{{ $v->recu ? 'Remplacer' : 'Joindre reçu' }}</button>
                                                    </form>
                                                    <form method="POST" action="{{ route('versements.destroy', $v) }}" onsubmit="return confirm('Supprimer ce versement ?');">
                                                        @csrf
                                                        <button type="submit" class="icon-btn danger">Supprimer</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="6" class="empty-state">Aucun versement enregistre.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>

                {{-- ===== AVANCEMENT DES TRAVAUX ===== --}}
                <section data-section="chantiers" style="display:none;">
                    <div class="panel">
                        <h3>🏗️ Ajouter une étape de chantier</h3>
                        <p class="subtitle">Les adhérents voient cet avancement dans leur espace et l'application mobile.</p>
                        <form method="POST" action="{{ route('chantiers.etapes.store') }}" enctype="multipart/form-data" class="form-grid" style="margin-top:.8rem;">
                            @csrf
                            <div>
                                <label>Programme</label>
                                <select name="programme_id" required>
                                    <option value="">Sélectionner...</option>
                                    @foreach ($programmes as $prog)<option value="{{ $prog->id }}">{{ $prog->name }}</option>@endforeach
                                </select>
                            </div>
                            <div><label>Titre de l'étape</label><input name="title" placeholder="Ex: Fondations, Toiture..." required></div>
                            <div><label>Avancement (%)</label><input name="progress" type="number" min="0" max="100" value="0" required></div>
                            <div><label>Ordre d'affichage</label><input name="ordre" type="number" min="0" value="0"></div>
                            <div><label>Date prévue</label><input name="date_prevue" type="date"></div>
                            <div><label>Date réalisée</label><input name="date_realisee" type="date"></div>
                            <div style="grid-column:1/-1"><label>Description</label><textarea name="description" placeholder="Détails..."></textarea></div>
                            <div style="grid-column:1/-1"><label>Photos du niveau (plusieurs possibles)</label><input name="photos[]" type="file" accept="image/*" multiple></div>
                            <div><button class="btn" type="submit">Ajouter l'étape</button></div>
                        </form>
                    </div>

                    @foreach ($programmes as $prog)
                        @if ($prog->etapes->count() > 0)
                            <div class="panel" style="margin-top:1rem;">
                                <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:.5rem;">
                                    <h3 style="margin:0">{{ $prog->name }}</h3>
                                    <span style="font-size:.85rem;color:var(--muted)">Avancement global : <strong style="color:var(--accent)">{{ $prog->avancementGlobal() }}%</strong></span>
                                </div>
                                <div class="progress" style="margin-top:.6rem;"><div class="progress-bar purple" style="width:{{ $prog->avancementGlobal() }}%"></div></div>
                                <div style="margin-top:1rem; display:grid; gap:.7rem;">
                                    @foreach ($prog->etapes as $etape)
                                        @php $imgs = $etape->images(); @endphp
                                        <div style="border:1px solid var(--border);border-radius:var(--radius-sm);padding:.7rem;">
                                            <div style="display:flex;justify-content:space-between;gap:.5rem;flex-wrap:wrap;align-items:center;">
                                                <strong style="font-size:.9rem">{{ $etape->title }} <span style="color:var(--muted);font-weight:500">· {{ count($imgs) }} photo(s)</span></strong>
                                                <div style="display:flex;gap:.4rem;align-items:center">
                                                    @if($etape->status==='termine')<span class="badge badge-green">Terminé</span>
                                                    @elseif($etape->status==='en_cours')<span class="badge badge-yellow">En cours</span>
                                                    @else<span class="badge" style="background:var(--surface2);color:var(--muted)">À venir</span>@endif
                                                    <span style="font-size:.8rem;color:var(--muted)">{{ $etape->progress }}%</span>
                                                </div>
                                            </div>
                                            <div class="progress" style="height:6px;margin-top:.5rem;"><div class="progress-bar purple" style="width:{{ $etape->progress }}%"></div></div>

                                            {{-- Galerie du niveau --}}
                                            @if (count($imgs) > 0)
                                                <div style="display:flex;gap:.5rem;flex-wrap:wrap;margin-top:.7rem;">
                                                    @foreach ($imgs as $img)
                                                        <div style="position:relative">
                                                            <img src="{{ $img['url'] }}" alt="" onclick="imgLightbox('{{ $img['url'] }}')" style="width:84px;height:64px;border-radius:8px;object-fit:cover;cursor:pointer;border:1px solid var(--border)">
                                                            @if ($img['id'])
                                                                <form method="POST" action="{{ route('chantiers.photos.destroy', $img['id']) }}" onsubmit="return confirm('Supprimer cette photo ?');" style="position:absolute;top:-6px;right:-6px;">
                                                                    @csrf
                                                                    <button type="submit" title="Supprimer" style="width:20px;height:20px;border-radius:50%;border:none;background:var(--danger);color:#fff;font-size:.7rem;cursor:pointer;line-height:1;">×</button>
                                                                </form>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif

                                            <details style="margin-top:.6rem">
                                                <summary style="cursor:pointer;color:var(--accent);font-size:.8rem;font-weight:600;">Modifier / ajouter des photos / supprimer</summary>
                                                <form method="POST" action="{{ route('chantiers.etapes.update', $etape) }}" enctype="multipart/form-data" class="form-grid" style="margin-top:.6rem;">
                                                    @csrf
                                                    <input type="hidden" name="programme_id" value="{{ $prog->id }}">
                                                    <div><label>Titre</label><input name="title" value="{{ $etape->title }}" required></div>
                                                    <div><label>Avancement (%)</label><input name="progress" type="number" min="0" max="100" value="{{ $etape->progress }}" required></div>
                                                    <div><label>Date réalisée</label><input name="date_realisee" type="date" value="{{ $etape->date_realisee?->format('Y-m-d') }}"></div>
                                                    <div><label>Ajouter des photos</label><input name="photos[]" type="file" accept="image/*" multiple></div>
                                                    <div style="grid-column:1/-1"><label>Description</label><textarea name="description">{{ $etape->description }}</textarea></div>
                                                    <div><button class="btn btn-sm" type="submit">Enregistrer</button></div>
                                                </form>
                                                <form method="POST" action="{{ route('chantiers.etapes.destroy', $etape) }}" onsubmit="return confirm('Supprimer cette étape ?');" style="margin-top:.5rem;">
                                                    @csrf
                                                    <button type="submit" class="icon-btn danger">Supprimer l'étape</button>
                                                </form>
                                            </details>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endforeach

                    @if ($programmes->sum(fn($p) => $p->etapes->count()) === 0)
                        <div class="panel" style="margin-top:1rem;"><div class="empty-state">Aucune étape de chantier. Ajoutez-en une ci-dessus.</div></div>
                    @endif
                </section>

                {{-- ===== BILAN FINANCIER ===== --}}
                <section data-section="bilan" style="display:none;">
                    <div class="panel">
                        <h3>📈 Bilan Financier</h3>
                        <p class="subtitle">Vue d'ensemble des finances avec filtres avancés</p>

                        <div class="sub-grid" style="margin-bottom:1rem; gap:.6rem;">
                            <div>
                                <label for="bilan_programme">Programme</label>
                                <select id="bilan_programme">
                                    <option value="">Tous les programmes</option>
                                    @foreach($programmes as $prog)
                                        <option value="{{ $prog->id }}">{{ $prog->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="bilan_method">Mode de paiement</label>
                                <select id="bilan_method">
                                    <option value="">Tous les modes</option>
                                    <option value="especes">Espèces</option>
                                    <option value="cheque">Chèque</option>
                                    <option value="virement">Virement</option>
                                    <option value="mobile_money">Mobile Money</option>
                                </select>
                            </div>
                            <div>
                                <label for="bilan_from">Du</label>
                                <input type="date" id="bilan_from">
                            </div>
                            <div>
                                <label for="bilan_to">Au</label>
                                <input type="date" id="bilan_to">
                            </div>
                        </div>
                    </div>

                    {{-- Cartes résumé --}}
                    <div class="stats" style="margin-top:1rem;" id="bilanStats">
                        <article class="stat"><h2>Total encaissé</h2><div class="val green" id="bilan_total_encaisse">0 FCFA</div></article>
                        <article class="stat"><h2>Prévision totale</h2><div class="val purple" id="bilan_total_prevision">{{ number_format($stats['total_prevision'], 0, ',', ' ') }} FCFA</div></article>
                        <article class="stat"><h2>Reste à percevoir</h2><div class="val red" id="bilan_reste">0 FCFA</div></article>
                        <article class="stat"><h2>Nb versements</h2><div class="val blue" id="bilan_nb_versements">0</div></article>
                        <article class="stat"><h2>Adhésions soldées</h2><div class="val green">{{ $stats['souscriptions_soldes'] }}</div></article>
                    </div>

                    {{-- Barre de progression globale --}}
                    <div class="panel" style="margin-top:1rem;">
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:.5rem;">
                            <span style="font-size:.85rem; font-weight:600; color:var(--muted);">Taux de recouvrement</span>
                            <span style="font-size:.95rem; font-weight:800; color:var(--accent);" id="bilan_pct">0%</span>
                        </div>
                        <div class="progress" style="height:14px;">
                            <div class="progress-bar purple" id="bilan_progress" style="width:0%"></div>
                        </div>
                    </div>

                    {{-- Répartition par programme --}}
                    <div class="panel" style="margin-top:1rem;" id="bilanByProgramme">
                        <h3>Répartition par programme</h3>
                        <div class="table-wrap" style="margin-top:.8rem;">
                            <table class="data-table">
                                <thead><tr><th>Programme</th><th>Adhésions</th><th>Prévision</th><th>Encaissé</th><th>Reste</th><th>Taux</th></tr></thead>
                                <tbody>
                                    @foreach($programmes as $prog)
                                        @php
                                            $progPrevision = $prog->souscriptions->sum(fn($s) => (float)$s->total_price);
                                            $progEncaisse = $prog->souscriptions->sum(fn($s) => $s->totalVerse());
                                            $progReste = $progPrevision - $progEncaisse;
                                            $progTaux = $progPrevision > 0 ? round($progEncaisse / $progPrevision * 100, 1) : 0;
                                        @endphp
                                        <tr>
                                            <td><strong>{{ $prog->name }}</strong><br><span style="font-size:.75rem;color:var(--muted)">{{ $prog->location }}</span></td>
                                            <td>{{ $prog->souscriptions_count }}</td>
                                            <td>{{ number_format($progPrevision, 0, ',', ' ') }}</td>
                                            <td class="money">{{ number_format($progEncaisse, 0, ',', ' ') }}</td>
                                            <td style="color:var(--danger); font-weight:700;">{{ number_format($progReste, 0, ',', ' ') }}</td>
                                            <td>
                                                <div class="progress" style="width:80px">
                                                    <div class="progress-bar {{ $progTaux >= 100 ? 'green' : ($progTaux >= 50 ? 'purple' : 'yellow') }}" style="width:{{ $progTaux }}%"></div>
                                                </div>
                                                <div class="progress-label">{{ $progTaux }}%</div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <!-- Courbe financière -->
                            <div class="panel" style="margin-top:1.5rem;">
                                <h3>Courbe des encaissements</h3>
                                <canvas id="bilanChart" height="80" style="max-width:100%;background:var(--surface2);border-radius:12px;"></canvas>
                            </div>
                        </div>
                    </div>

                    {{-- Répartition par mode de paiement --}}
                    <div class="panel" style="margin-top:1rem;">
                        <h3>Répartition par mode de paiement</h3>
                        <div id="bilanByMethod" style="display:grid; grid-template-columns:repeat(auto-fill,minmax(180px,1fr)); gap:.7rem; margin-top:.8rem;"></div>
                    </div>

                    {{-- Tableau détaillé filtrable --}}
                    <div class="panel" style="margin-top:1rem;">
                        <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:.5rem;">
                            <h3>Détail des versements</h3>
                            <span style="font-size:.82rem; color:var(--muted); font-weight:600;" id="bilan_count_label">0 résultats</span>
                        </div>
                        <div class="table-wrap" style="margin-top:.8rem;">
                            <table class="data-table" id="bilanTable">
                                <thead><tr><th>Date</th><th>Adhérent</th><th>Programme</th><th>Montant</th><th>Mode</th><th>Référence</th><th>PDF</th></tr></thead>
                                <tbody id="bilanTableBody">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>

                {{-- ===== JOURNAL D'AUDIT ===== --}}
                <section data-section="audit" style="display:none;">
                    <div class="panel">
                        <h3>🛡️ Journal d'audit</h3>
                        <p class="subtitle">Traçabilité des créations, modifications et suppressions (120 dernières actions).</p>
                        <div class="table-wrap" style="margin-top:.8rem;">
                            <table class="data-table">
                                <thead><tr><th>Date</th><th>Administrateur</th><th>Action</th><th>Objet</th><th>Détail</th></tr></thead>
                                <tbody>
                                    @forelse ($auditLogs as $log)
                                        <tr>
                                            <td style="white-space:nowrap">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                                            <td>{{ $log->user_name ?? '—' }}</td>
                                            <td>
                                                @if($log->action==='created')<span class="badge badge-green">Création</span>
                                                @elseif($log->action==='updated')<span class="badge badge-yellow">Modification</span>
                                                @else<span class="badge badge-red">Suppression</span>@endif
                                            </td>
                                            <td><strong>{{ $log->model_type }}</strong><div style="font-size:.75rem;color:var(--muted)">{{ $log->summary }}</div></td>
                                            <td style="max-width:320px">
                                                @if($log->changes)
                                                    <span style="font-size:.74rem;color:var(--muted)">{{ collect($log->changes)->map(fn($v,$k)=>$k.': '.(is_scalar($v)?$v:json_encode($v)))->implode(' · ') }}</span>
                                                @else — @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="empty-state">Aucune action enregistrée pour le moment.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>

                {{-- ===== PARAMÈTRES (en-têtes documents) ===== --}}
                <section data-section="parametres" style="display:none;">
                    <div class="panel">
                        <h3>⚙️ Identité de l'entreprise</h3>
                        <p class="subtitle">Ces informations apparaissent en en-tête des factures, attestations et reçus PDF.</p>

                        <div style="display:flex;gap:1.2rem;flex-wrap:wrap;align-items:center;margin:1rem 0 1.2rem;">
                            <img src="{{ \App\Models\Setting::get('company_logo') ? asset('storage/'.\App\Models\Setting::get('company_logo')) : asset('image/lorny.png') }}"
                                 alt="Logo" style="height:80px;border-radius:10px;border:1px solid var(--border);background:#fff;padding:4px;object-fit:contain">
                            <div style="font-size:.84rem;color:var(--muted)">
                                Logo actuel.<br>Format conseillé : PNG/JPG carré, fond clair.
                                @if(\App\Models\Setting::get('company_logo'))
                                    <form method="POST" action="{{ route('settings.logo.reset') }}" style="margin-top:.5rem">
                                        @csrf
                                        <button type="submit" class="icon-btn">Rétablir le logo par défaut</button>
                                    </form>
                                @endif
                            </div>
                        </div>

                        <form method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data" class="form-grid">
                            @csrf
                            <div><label>Nom de l'entreprise</label><input name="company_name" value="{{ \App\Models\Setting::get('company_name') }}" required></div>
                            <div><label>Slogan / activité</label><input name="company_tagline" value="{{ \App\Models\Setting::get('company_tagline') }}" placeholder="Ex: Logements & Gestion"></div>
                            <div style="grid-column:1/-1"><label>Adresse</label><input name="company_address" value="{{ \App\Models\Setting::get('company_address') }}" placeholder="Ex: Cocody, Abidjan"></div>
                            <div><label>Téléphone</label><input name="company_phone" value="{{ \App\Models\Setting::get('company_phone') }}" placeholder="+225 ..."></div>
                            <div><label>Email</label><input name="company_email" value="{{ \App\Models\Setting::get('company_email') }}" placeholder="contact@imlux.ci"></div>
                            <div><label>RCCM (registre du commerce)</label><input name="company_rccm" value="{{ \App\Models\Setting::get('company_rccm') }}"></div>
                            <div><label>NCC (n° compte contribuable)</label><input name="company_ncc" value="{{ \App\Models\Setting::get('company_ncc') }}"></div>
                            <div><label>Site web</label><input name="company_website" value="{{ \App\Models\Setting::get('company_website') }}" placeholder="www.imlux.ci"></div>
                            <div><label>Remplacer le logo</label><input name="logo" type="file" accept="image/*"></div>
                            <div style="grid-column:1/-1"><label>Note de bas de page des documents</label><textarea name="company_footer" placeholder="Ex: Merci de votre confiance.">{{ \App\Models\Setting::get('company_footer') }}</textarea></div>
                            <div><button class="btn" type="submit">Enregistrer les paramètres</button></div>
                        </form>
                    </div>
                </section>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Données passées depuis le contrôleur (tableau plat de versements)
            const bilanData = @json($bilanData);
            const versements = Array.isArray(bilanData) ? bilanData : [];
            // Grouper par mois
            const monthly = {};
            versements.forEach(v => {
                const d = new Date(v.date);
                const key = d.getFullYear() + '-' + String(d.getMonth()+1).padStart(2,'0');
                monthly[key] = (monthly[key]||0) + parseFloat(v.amount);
            });
            const labels = Object.keys(monthly).sort();
            const data = labels.map(k => monthly[k]);
            // Création du graphique
            const ctx = document.getElementById('bilanChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Montant encaissé',
                        data: data,
                        borderColor: '#ED8B1C',
                        backgroundColor: 'rgba(237,139,28,0.15)',
                        fill: true,
                        tension: 0.3,
                        pointRadius: 4,
                        pointBackgroundColor: '#ED8B1C',
                        pointBorderColor: '#fff',
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    plugins: {
                        legend: { labels: { color: '#0d1a33', font: { weight: 700 } } }
                    },
                    scales: {
                        x: { ticks: { color: '#5b647a' }, grid: { color: 'rgba(237,139,28,.1)' } },
                        y: { ticks: { color: '#5b647a' }, grid: { color: 'rgba(237,139,28,.1)' } }
                    }
                }
            });
        });
        </script>

            </main>
        </div>
    </div>

    {{-- ===== MODALES D'ÉDITION ===== --}}
    <div class="modal-overlay" id="modal-programme">
        <div class="modal">
            <div class="modal-head"><h3>Modifier le programme</h3><button type="button" class="modal-close" data-close>&times;</button></div>
            <form method="POST" id="form-programme" class="form-grid">
                @csrf
                <div><label>Nom du programme</label><input name="name" id="ep-name" required></div>
                <div><label>Localisation</label><input name="location" id="ep-location" required></div>
                <div>
                    <label>Statut</label>
                    <select name="status" id="ep-status">
                        <option value="actif">Actif</option>
                        <option value="suspendu">Suspendu</option>
                        <option value="termine">Termine</option>
                    </select>
                </div>
                <div><label>Surface utile (m²)</label><input name="surface_utile" id="ep-surface_utile" type="number" step="0.01" min="0"></div>
                <div><label>Surface totale (m²)</label><input name="surface_totale" id="ep-surface_totale" type="number" step="0.01" min="0"></div>
                <div style="grid-column:1/-1"><label>Description</label><textarea name="description" id="ep-description"></textarea></div>
                <div><button class="btn" type="submit">Enregistrer</button></div>
            </form>
        </div>
    </div>

    <div class="modal-overlay" id="modal-lot">
        <div class="modal">
            <div class="modal-head"><h3>Modifier le lot</h3><button type="button" class="modal-close" data-close>&times;</button></div>
            <form method="POST" id="form-lot" class="form-grid">
                @csrf
                <div><label>Reference</label><input name="reference" id="el-reference" required></div>
                <div>
                    <label>Type de logement</label>
                    <select name="type_logement" id="el-type_logement" required>
                        <option value="studio">Studio</option>
                        <option value="F2">F2</option>
                        <option value="F3">F3</option>
                        <option value="F4">F4</option>
                        <option value="villa">Villa</option>
                        <option value="duplex">Duplex</option>
                        <option value="terrain">Terrain</option>
                    </select>
                </div>
                <div><label>Prix (FCFA)</label><input name="price" id="el-price" type="number" step="0.01" min="0" required></div>
                <div><label>Surface (m²)</label><input name="surface" id="el-surface" type="number" step="0.01" min="0"></div>
                <div>
                    <label>Statut</label>
                    <select name="status" id="el-status" required>
                        <option value="disponible">Disponible</option>
                        <option value="reserve">Reserve</option>
                        <option value="vendu">Vendu</option>
                    </select>
                </div>
                <div style="grid-column:1/-1"><label>Description</label><textarea name="description" id="el-description"></textarea></div>
                <div><button class="btn" type="submit">Enregistrer</button></div>
            </form>
        </div>
    </div>

    <div class="modal-overlay" id="modal-souscripteur">
        <div class="modal">
            <div class="modal-head"><h3>Modifier l'adhérent</h3><button type="button" class="modal-close" data-close>&times;</button></div>
            <form method="POST" id="form-souscripteur" class="form-grid">
                @csrf
                <div><label>Prenom</label><input name="first_name" id="es-first_name" required></div>
                <div><label>Nom</label><input name="last_name" id="es-last_name" required></div>
                <div><label>Email</label><input name="email" id="es-email" type="email"></div>
                <div><label>Telephone</label><input name="phone" id="es-phone"></div>
                <div><label>Date de naissance</label><input name="date_naissance" id="es-date_naissance" type="date"></div>
                <div>
                    <label>Type de piece</label>
                    <select name="id_type" id="es-id_type">
                        <option value="">Aucune</option>
                        <option value="CNI">CNI</option>
                        <option value="Passeport">Passeport</option>
                        <option value="Attestation">Attestation</option>
                        <option value="Permis">Permis de conduire</option>
                    </select>
                </div>
                <div><label>Numero de piece</label><input name="id_number" id="es-id_number"></div>
                <div style="grid-column:1/-1"><label>Adresse</label><textarea name="address" id="es-address" style="min-height:60px"></textarea></div>

                <div style="grid-column:1/-1;border-top:1px solid var(--border);padding-top:.9rem;margin-top:.3rem">
                    <label style="text-transform:none;font-size:.82rem;color:var(--accent)">📱 Accès à l'application mobile</label>
                    <label style="display:flex;align-items:center;gap:.5rem;text-transform:none;color:var(--text);font-weight:500;margin-top:.4rem">
                        <input type="checkbox" name="app_access" id="es-app_access" value="1" style="width:auto">
                        Autoriser cet adhérent à se connecter à l'application
                    </label>
                </div>
                <div style="grid-column:1/-1">
                    <label>Mot de passe de l'app <span style="text-transform:none;font-weight:400">(laisser vide pour ne pas changer)</span></label>
                    <input name="password" id="es-password" type="text" autocomplete="new-password" placeholder="Min. 6 caractères">
                    <small style="color:var(--muted);font-size:.76rem">L'email de l'adhérent sert d'identifiant de connexion.</small>
                </div>

                <div style="grid-column:1/-1;border-top:1px solid var(--border);padding-top:.9rem;margin-top:.3rem">
                    <label style="text-transform:none;font-size:.82rem;color:var(--accent)">📁 Frais d'ouverture de dossier <span style="color:var(--muted)">(hors prix du bien)</span></label>
                </div>
                <div><label>Montant des frais (FCFA)</label><input name="frais_ouverture" id="es-frais_ouverture" type="number" step="0.01" min="0"></div>
                <div style="display:flex;align-items:flex-end">
                    <label style="display:flex;align-items:center;gap:.5rem;text-transform:none;color:var(--text);font-weight:500;margin:0 0 .6rem">
                        <input type="checkbox" name="frais_ouverture_payes" id="es-frais_ouverture_payes" value="1" style="width:auto">
                        Frais payés
                    </label>
                </div>

                <div><button class="btn" type="submit">Enregistrer</button></div>
            </form>
        </div>
    </div>

    <div class="modal-overlay" id="modal-souscription">
        <div class="modal">
            <div class="modal-head"><h3>Modifier l'adhésion</h3><button type="button" class="modal-close" data-close>&times;</button></div>
            <form method="POST" id="form-souscription" class="form-grid">
                @csrf
                <div><label>Prix total (FCFA)</label><input name="total_price" id="eu-total_price" type="number" step="0.01" min="0" required></div>
                <div><label>Nombre d'échéances</label><input name="nb_mensualites" id="eu-nb_mensualites" type="number" min="1" max="360" required></div>
                <div>
                    <label>Rythme de règlement</label>
                    <select name="rythme" id="eu-rythme" required>
                        @foreach (\App\Models\Souscription::RYTHMES as $val => $lib)
                            <option value="{{ $val }}">{{ $lib }}</option>
                        @endforeach
                    </select>
                </div>
                <div><label>Date de souscription</label><input name="date_souscription" id="eu-date_souscription" type="date" required></div>
                <div>
                    <label>Statut</label>
                    <select name="status" id="eu-status" required>
                        <option value="en_cours">En cours</option>
                        <option value="solde">Solde</option>
                        <option value="annule">Annule</option>
                    </select>
                </div>
                <div style="grid-column:1/-1"><label>Notes</label><textarea name="notes" id="eu-notes"></textarea></div>
                <div style="grid-column:1/-1"><small style="color:var(--muted)">La mensualite est recalculee automatiquement. Le statut du lot suit celui de l'adhésion (annule → disponible, solde → vendu).</small></div>
                <div><button class="btn" type="submit">Enregistrer</button></div>
            </form>
        </div>
    </div>

    <div class="modal-overlay" id="modal-bien">
        <div class="modal">
            <div class="modal-head"><h3>Modifier le bien</h3><button type="button" class="modal-close" data-close>&times;</button></div>
            <form method="POST" id="form-bien" enctype="multipart/form-data" class="form-grid">
                @csrf
                <div><label>Nom</label><input name="name" id="eb-name" required></div>
                <div><label>Type / description</label><input name="type" id="eb-type"></div>
                <div><label>Surface (m²)</label><input name="surface" id="eb-surface" type="number" step="0.01" min="0"></div>
                <div><label>Prix (FCFA)</label><input name="price" id="eb-price" type="number" step="0.01" min="0" required></div>
                <div><label>Apport (%)</label><input name="apport_pct" id="eb-apport_pct" type="number" min="0" max="100" required></div>
                <div style="display:flex;align-items:flex-end"><label style="display:flex;align-items:center;gap:.5rem;text-transform:none;color:var(--text);font-weight:500;margin:0 0 .6rem"><input type="checkbox" name="cloture_incluse" id="eb-cloture_incluse" value="1" style="width:auto"> Livré avec clôture</label></div>
                <div><label>Prix clôture (FCFA)</label><input name="cloture_prix" id="eb-cloture_prix" type="number" step="0.01" min="0"></div>
                <div>
                    <label>Statut</label>
                    <select name="status" id="eb-status" required><option value="disponible">Disponible</option><option value="reserve">Réservé</option><option value="vendu">Vendu</option></select>
                </div>
                <div><label>Ordre</label><input name="ordre" id="eb-ordre" type="number" min="0"></div>
                <div style="grid-column:1/-1"><label>Description</label><textarea name="description" id="eb-description"></textarea></div>
                <div style="grid-column:1/-1"><label>Remplacer la photo</label><input name="photo" type="file" accept="image/*"></div>
                <div><button class="btn" type="submit">Enregistrer</button></div>
            </form>
        </div>
    </div>

    {{-- ===== LIGHTBOX (agrandissement des photos) ===== --}}
    <div id="imgLightbox" onclick="this.style.display='none'" style="display:none;position:fixed;inset:0;z-index:60;background:rgba(8,16,12,.85);align-items:center;justify-content:center;padding:1.5rem;cursor:zoom-out;">
        <img id="imgLightboxImg" src="" alt="" style="max-width:95%;max-height:92%;border-radius:12px;box-shadow:0 30px 60px rgba(0,0,0,.5)">
    </div>
    <script>
        function imgLightbox(url) {
            var box = document.getElementById('imgLightbox');
            document.getElementById('imgLightboxImg').src = url;
            box.style.display = 'flex';
        }
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') document.getElementById('imgLightbox').style.display = 'none';
        });
    </script>

    <script>
        // ===== MODALES (édition) =====
        (function () {
            var routes = {
                programme: "{{ url('admin/programmes') }}/",
                lot: "{{ url('admin/lots') }}/",
                souscripteur: "{{ url('admin/souscripteurs') }}/",
                souscription: "{{ url('admin/souscriptions') }}/",
                bien: "{{ url('admin/biens') }}/"
            };
            var prefix = { programme: 'ep', lot: 'el', souscripteur: 'es', souscription: 'eu', bien: 'eb' };

            function openModal(id) { var m = document.getElementById('modal-' + id); if (m) m.classList.add('open'); }
            function closeAll() { document.querySelectorAll('.modal-overlay').forEach(function (m) { m.classList.remove('open'); }); }

            document.querySelectorAll('[data-edit]').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var type = btn.getAttribute('data-edit');
                    var form = document.getElementById('form-' + type);
                    if (!form) return;
                    form.action = routes[type] + btn.getAttribute('data-id');
                    var p = prefix[type];
                    // Réinitialise le champ mot de passe (jamais pré-rempli)
                    var pwd = document.getElementById(p + '-password');
                    if (pwd) pwd.value = '';
                    Object.keys(btn.dataset).forEach(function (key) {
                        if (key === 'edit' || key === 'id') return;
                        var field = document.getElementById(p + '-' + key);
                        if (!field) return;
                        if (field.type === 'checkbox') {
                            field.checked = btn.dataset[key] === '1' || btn.dataset[key] === 'true';
                        } else {
                            field.value = btn.dataset[key];
                        }
                    });
                    openModal(type);
                });
            });

            document.querySelectorAll('[data-close]').forEach(function (b) { b.addEventListener('click', closeAll); });
            document.querySelectorAll('.modal-overlay').forEach(function (m) {
                m.addEventListener('click', function (e) { if (e.target === m) closeAll(); });
            });
            document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeAll(); });
        })();
    </script>

    <script>
        // Tab navigation
        (function () {
            var links = Array.from(document.querySelectorAll('[data-tab-target]'));
            var sections = Array.from(document.querySelectorAll('[data-section]'));

            function activate(target) {
                var tab = target || 'dashboard';
                links.forEach(function (l) { l.classList.toggle('active', l.getAttribute('data-tab-target') === tab); });
                sections.forEach(function (s) { s.style.display = s.getAttribute('data-section') === tab ? '' : 'none'; });
                // Force Chart.js à recalculer ses dimensions une fois le canvas visible
                window.dispatchEvent(new Event('resize'));
            }

            links.forEach(function (l) {
                l.addEventListener('click', function () {
                    activate(l.getAttribute('data-tab-target'));
                    var sb = document.getElementById('sidebarMenu');
                    if (sb && window.innerWidth < 1024) sb.classList.remove('open');
                });
            });

            var hash = window.location.hash ? window.location.hash.replace('#', '') : 'dashboard';
            activate(hash);
        })();

        // Hamburger
        (function () {
            var btn = document.getElementById('hamburgerBtn');
            var sb = document.getElementById('sidebarMenu');
            if (btn && sb) btn.addEventListener('click', function () { sb.classList.toggle('open'); });
        })();

        // Lot filter by programme
        (function () {
            var progSelect = document.getElementById('sousc_programme');
            var lotSelect = document.getElementById('sousc_lot');
            var priceInput = document.getElementById('sousc_price');
            if (!progSelect || !lotSelect) return;

            progSelect.addEventListener('change', function () {
                var pid = this.value;
                var opts = lotSelect.querySelectorAll('option[data-programme]');
                opts.forEach(function (o) {
                    o.style.display = (!pid || o.getAttribute('data-programme') === pid) ? '' : 'none';
                    o.selected = false;
                });
                lotSelect.value = '';
                if (priceInput) priceInput.value = '';
            });

            lotSelect.addEventListener('change', function () {
                var sel = this.options[this.selectedIndex];
                if (sel && sel.dataset.price && priceInput) {
                    priceInput.value = sel.dataset.price;
                }
            });
        })();

        // ===== VERSEMENT : client -> dossier -> reste à payer =====
        (function () {
            var clientSel = document.getElementById('vers_client');
            var souscSel = document.getElementById('vers_souscription');
            var resume = document.getElementById('vers_resume');
            var amountInput = document.getElementById('vers_amount');
            if (!clientSel || !souscSel) return;

            function fmt(n) { return Math.round(n).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ') + ' FCFA'; }

            function filterDossiers() {
                var cid = clientSel.value;
                var opts = souscSel.querySelectorAll('option[data-client]');
                opts.forEach(function (o) {
                    o.hidden = !(cid && o.getAttribute('data-client') === cid);
                    o.selected = false;
                });
                souscSel.value = '';
                resume.style.display = 'none';
                if (amountInput) amountInput.removeAttribute('max');
            }

            function showReste() {
                var o = souscSel.options[souscSel.selectedIndex];
                if (!o || !o.dataset.reste) { resume.style.display = 'none'; return; }
                document.getElementById('vers_verse').textContent = fmt(parseFloat(o.dataset.verse));
                document.getElementById('vers_total').textContent = fmt(parseFloat(o.dataset.total));
                document.getElementById('vers_reste').textContent = fmt(parseFloat(o.dataset.reste));
                resume.style.display = 'block';
                if (amountInput) {
                    amountInput.max = o.dataset.reste;
                    if (!amountInput.value) amountInput.value = o.dataset.reste;
                }
            }

            clientSel.addEventListener('change', filterDossiers);
            souscSel.addEventListener('change', showReste);
            filterDossiers();
        })();

        // ===== SOUSCRIPTION : déduction de l'échéancier =====
        (function () {
            var price = document.getElementById('sousc_price');
            var apport = document.getElementById('sousc_apport');
            var rythme = document.getElementById('sousc_rythme');
            var duree = document.getElementById('sousc_duree');
            var nb = document.getElementById('sousc_nb');
            var box = document.getElementById('sousc_echeancier');
            if (!price || !nb) return;

            var intervals = { mensuel: 1, trimestriel: 3 };
            var pct = {{ (int) \App\Models\Setting::get('apport_min_pct', '35') }};
            var hint = document.getElementById('sousc_apport_hint');
            var lock = document.getElementById('sousc_lock');
            function fmt(n) { return Math.round(n).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ') + ' FCFA'; }

            function deduireNb() {
                var d = parseInt(duree.value, 10);
                var it = intervals[rythme.value] || 1;
                if (d > 0) { nb.value = Math.max(1, Math.ceil(d / it)); }
                recalc();
            }

            function recalc() {
                var total = parseFloat(price.value) || 0;
                var ap = parseFloat(apport.value) || 0;
                var n = parseInt(nb.value, 10) || 0;
                var requis = total * pct / 100;
                var reste = Math.max(0, total - ap);
                var debloque = total > 0 && ap >= requis;

                // Indice sous le champ Apport
                if (hint) {
                    if (total > 0) {
                        if (debloque) {
                            hint.style.color = 'var(--success)';
                            hint.textContent = '✓ Seuil ' + pct + ' % atteint (' + fmt(requis) + ') — échéances débloquées';
                        } else {
                            hint.style.color = 'var(--warning)';
                            hint.textContent = '⚠ Apport minimum ' + pct + ' % = ' + fmt(requis) + ' · reste ' + fmt(requis - ap) + ' avant le démarrage des échéances';
                        }
                    } else {
                        hint.textContent = '';
                    }
                }

                // Bandeau verrou
                if (lock) {
                    if (total > 0 && !debloque) {
                        document.getElementById('sousc_pct').textContent = pct;
                        document.getElementById('sousc_requis').textContent = fmt(requis);
                        document.getElementById('sousc_reste_apport').textContent = fmt(requis - ap);
                        lock.style.display = 'block';
                    } else {
                        lock.style.display = 'none';
                    }
                }

                // Aperçu de l'échéancier (affiché seulement une fois le seuil atteint)
                if (debloque && n > 0) {
                    document.getElementById('sousc_montant_ech').textContent = fmt(reste / n);
                    document.getElementById('sousc_reste_ech').textContent = fmt(reste);
                    document.getElementById('sousc_nb_ech').textContent = n;
                    box.style.display = 'block';
                } else {
                    box.style.display = 'none';
                }
            }

            var form = document.getElementById('souscriptionForm');
            if (form) {
                form.addEventListener('submit', function (e) {
                    var total = parseFloat(price.value) || 0;
                    var ap = parseFloat(apport.value) || 0;
                    var requis = total * pct / 100;
                    if (total > 0 && ap < requis) {
                        if (!confirm('L\'apport initial est inférieur à ' + pct + ' % (' + fmt(requis) + ').\nLes échéances ne démarreront qu\'une fois ce seuil atteint. Continuer ?')) {
                            e.preventDefault();
                        }
                    }
                });
            }

            duree.addEventListener('input', deduireNb);
            rythme.addEventListener('change', deduireNb);
            [price, apport, nb].forEach(function (el) { el.addEventListener('input', recalc); });
            recalc();
        })();

        // ===== BILAN FINANCIER FILTRES =====
        (function () {
            var allVersements = @json($bilanData);

            var totalPrevision = {{ (float) $stats['total_prevision'] }};

            var fProg = document.getElementById('bilan_programme');
            var fMethod = document.getElementById('bilan_method');
            var fFrom = document.getElementById('bilan_from');
            var fTo = document.getElementById('bilan_to');

            function fmt(n) {
                return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
            }

            function methodLabel(m) {
                var labels = {especes:'Espèces', cheque:'Chèque', virement:'Virement', mobile_money:'Mobile Money'};
                return labels[m] || m;
            }

            function methodColors(m) {
                var c = {
                    especes: {bg:'var(--success-bg)', color:'var(--success)', border:'rgba(52,211,153,.2)'},
                    cheque: {bg:'var(--info-bg)', color:'var(--info)', border:'rgba(46,139,95,.2)'},
                    virement: {bg:'rgba(237,139,28,.1)', color:'var(--accent)', border:'rgba(237,139,28,.2)'},
                    mobile_money: {bg:'var(--warning-bg)', color:'var(--warning)', border:'rgba(251,191,36,.2)'}
                };
                return c[m] || {bg:'var(--surface2)', color:'var(--muted)', border:'var(--border)'};
            }

            function applyFilters() {
                var prog = fProg.value;
                var method = fMethod.value;
                var from = fFrom.value;
                var to = fTo.value;

                var filtered = allVersements.filter(function (v) {
                    if (prog && v.programme_id != prog) return false;
                    if (method && v.method !== method) return false;
                    if (from && v.date < from) return false;
                    if (to && v.date > to) return false;
                    return true;
                });

                // Stats
                var totalEncaisse = filtered.reduce(function (s, v) { return s + v.amount; }, 0);
                var reste = totalPrevision - totalEncaisse;
                var pct = totalPrevision > 0 ? (totalEncaisse / totalPrevision * 100).toFixed(1) : 0;

                document.getElementById('bilan_total_encaisse').textContent = fmt(Math.round(totalEncaisse)) + ' FCFA';
                document.getElementById('bilan_reste').textContent = fmt(Math.max(0, Math.round(reste))) + ' FCFA';
                document.getElementById('bilan_nb_versements').textContent = filtered.length;
                document.getElementById('bilan_pct').textContent = pct + '%';
                document.getElementById('bilan_progress').style.width = Math.min(pct, 100) + '%';
                document.getElementById('bilan_count_label').textContent = filtered.length + ' résultats';

                // By method
                var byMethod = {};
                filtered.forEach(function (v) {
                    if (!byMethod[v.method]) byMethod[v.method] = {count: 0, total: 0};
                    byMethod[v.method].count++;
                    byMethod[v.method].total += v.amount;
                });
                var methodHtml = '';
                Object.keys(byMethod).forEach(function (m) {
                    var c = methodColors(m);
                    methodHtml += '<div style="background:' + c.bg + '; border:1px solid ' + c.border + '; border-radius:var(--radius-sm); padding:1rem;">'
                        + '<div style="font-size:.72rem; font-weight:600; color:' + c.color + '; text-transform:uppercase; letter-spacing:.04em;">' + methodLabel(m) + '</div>'
                        + '<div style="font-size:1.3rem; font-weight:800; color:' + c.color + '; margin-top:.3rem;">' + fmt(Math.round(byMethod[m].total)) + ' <small style="font-size:.6em">FCFA</small></div>'
                        + '<div style="font-size:.78rem; color:var(--muted); margin-top:.2rem;">' + byMethod[m].count + ' versement' + (byMethod[m].count > 1 ? 's' : '') + '</div>'
                        + '</div>';
                });
                document.getElementById('bilanByMethod').innerHTML = methodHtml || '<div class="empty-state">Aucune donnée.</div>';

                // Table
                var tbody = document.getElementById('bilanTableBody');
                var html = '';
                filtered.forEach(function (v) {
                    html += '<tr>'
                        + '<td>' + v.date_display + '</td>'
                        + '<td>' + v.souscripteur + '</td>'
                        + '<td>' + v.programme + '</td>'
                        + '<td class="money">' + fmt(Math.round(v.amount)) + ' FCFA</td>'
                        + '<td><span class="badge badge-blue">' + methodLabel(v.method) + '</span></td>'
                        + '<td>' + v.reference + '</td>'
                        + '<td><a href="' + v.facture_url + '" target="_blank" style="color:var(--accent); font-weight:600; text-decoration:none; font-size:.82rem;">Facture</a></td>'
                        + '</tr>';
                });
                tbody.innerHTML = html || '<tr><td colspan="7" class="empty-state">Aucun versement pour ces filtres.</td></tr>';
            }

            [fProg, fMethod, fFrom, fTo].forEach(function (el) {
                if (el) el.addEventListener('change', applyFilters);
            });

            applyFilters();
        })();
    </script>
</body>
</html>
