<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PROJET IM'LUX — Suivi de {{ $souscripteur->fullName() }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="icon" type="image/jpeg" href="{{ asset('image/logo.jpeg') }}">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #f6f4ee;
            --surface: #ffffff;
            --surface2: #f3f1e9;
            --border: #e6e0d2;
            --text: #1d2b22;
            --muted: #6b7770;
            --accent: #a8801e;
            --accent2: #9c7b2e;
            --success: #1f8a5a;
            --success-bg: rgba(31,138,90,.12);
            --warning: #b9831f;
            --warning-bg: rgba(185,131,31,.12);
            --danger: #c2412f;
            --danger-bg: rgba(194,65,47,.1);
            --info: #1f6b4c;
            --glass: rgba(255,255,255,.7);
            --radius: 16px;
            --radius-sm: 12px;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            padding: 1rem;
        }
        .page { max-width: 960px; margin: 0 auto; }

        /* Header */
        .header {
            display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;
            margin-bottom: 1.5rem;
        }
        .brand {
            font-size: 1.4rem; font-weight: 800;
            background: linear-gradient(120deg, #9c7b2e, #c8a24a 35%, #1f6b4c);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            text-decoration: none;
        }
        .back-btn {
            padding: .5rem 1rem; border-radius: var(--radius-sm);
            background: var(--surface); border: 1px solid var(--border);
            color: var(--muted); font: inherit; font-size: .85rem; font-weight: 600;
            text-decoration: none; transition: all .2s;
        }
        .back-btn:hover { color: var(--text); border-color: var(--accent); }

        /* Profile card */
        .profile-card {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: 22px; padding: 1.8rem; margin-bottom: 1.5rem;
            position: relative; overflow: hidden;
        }
        .profile-card::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px;
            background: linear-gradient(135deg, #e8ce7e, #c8a24a 55%, #9c7b2e);
        }
        .profile-header { display: flex; align-items: center; gap: 1.2rem; flex-wrap: wrap; margin-top: .5rem; }
        .avatar {
            width: 64px; height: 64px; border-radius: 18px;
            background: linear-gradient(135deg, #c8a24a, #9c7b2e);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.6rem; font-weight: 800; color: #fff; flex-shrink: 0;
        }
        .avatar img { width: 100%; height: 100%; object-fit: cover; border-radius: 18px; }
        .profile-info h2 { font-size: 1.3rem; font-weight: 700; }
        .uid-tag {
            display: inline-block; margin-top: .35rem;
            padding: .25rem .65rem; border-radius: 8px;
            background: rgba(200,162,74,.12); border: 1px solid rgba(200,162,74,.25);
            font-size: .78rem; font-weight: 700; color: var(--accent);
            letter-spacing: .03em;
        }
        .profile-details {
            display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: .75rem; margin-top: 1.2rem;
        }
        .detail-item { font-size: .85rem; }
        .detail-item .label { color: var(--muted); font-size: .75rem; text-transform: uppercase; letter-spacing: .04em; margin-bottom: .15rem; }

        /* Souscription card */
        .sousc-card {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: 20px; margin-bottom: 1.5rem; overflow: hidden;
        }
        .sousc-header {
            padding: 1.5rem; display: flex; align-items: flex-start; justify-content: space-between; flex-wrap: wrap; gap: 1rem;
            border-bottom: 1px solid var(--border);
        }
        .sousc-title { font-size: 1.1rem; font-weight: 700; }
        .sousc-subtitle { font-size: .85rem; color: var(--muted); margin-top: .25rem; }
        .badge {
            padding: .3rem .7rem; border-radius: 8px; font-size: .75rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: .04em;
        }
        .badge-success { background: var(--success-bg); color: var(--success); border: 1px solid rgba(52,211,153,.25); }
        .badge-warning { background: var(--warning-bg); color: var(--warning); border: 1px solid rgba(251,191,36,.25); }
        .badge-danger { background: var(--danger-bg); color: var(--danger); border: 1px solid rgba(248,113,113,.25); }

        /* Stats grid */
        .stats {
            display: grid; grid-template-columns: repeat(auto-fill, minmax(170px, 1fr));
            gap: 0; border-bottom: 1px solid var(--border);
        }
        .stat {
            padding: 1.2rem 1.5rem;
            border-right: 1px solid var(--border);
        }
        .stat:last-child { border-right: none; }
        .stat-label { font-size: .72rem; font-weight: 600; color: var(--muted); text-transform: uppercase; letter-spacing: .04em; }
        .stat-value { font-size: 1.25rem; font-weight: 800; margin-top: .3rem; }
        .stat-value.accent { color: var(--accent); }
        .stat-value.success { color: var(--success); }
        .stat-value.danger { color: var(--danger); }

        /* Progress bar */
        .progress-wrap { padding: 1rem 1.5rem; border-bottom: 1px solid var(--border); }
        .progress-label { display: flex; justify-content: space-between; font-size: .82rem; margin-bottom: .5rem; }
        .progress-label span:first-child { color: var(--muted); font-weight: 600; }
        .progress-label span:last-child { font-weight: 700; color: var(--accent); }
        .progress-bar { height: 10px; background: rgba(200,162,74,.1); border-radius: 10px; overflow: hidden; }
        .progress-fill {
            height: 100%; border-radius: 10px;
            background: linear-gradient(90deg, #9c7b2e, #c8a24a, #e8ce7e);
            transition: width .6s ease;
        }

        /* Versements table */
        .versements-section { padding: 1.5rem; }
        .versements-title { font-size: .95rem; font-weight: 700; margin-bottom: 1rem; }
        .table-wrap { overflow-x: auto; margin: 0 -.5rem; }
        table { width: 100%; border-collapse: collapse; font-size: .85rem; }
        thead th {
            text-align: left; padding: .6rem .75rem;
            font-size: .72rem; font-weight: 600; color: var(--muted);
            text-transform: uppercase; letter-spacing: .04em;
            border-bottom: 1px solid var(--border);
        }
        tbody td {
            padding: .7rem .75rem;
            border-bottom: 1px solid rgba(148,163,184,.08);
        }
        tbody tr:hover { background: rgba(200,162,74,.04); }
        .method-badge {
            display: inline-block; padding: .15rem .5rem; border-radius: 6px;
            font-size: .72rem; font-weight: 600;
            background: rgba(200,162,74,.1); color: var(--accent);
        }

        .no-data {
            text-align: center; padding: 2rem; color: var(--muted); font-size: .9rem;
        }

        /* Empty state */
        .empty-state {
            text-align: center; padding: 4rem 2rem;
            background: var(--surface); border: 1px solid var(--border);
            border-radius: 22px;
        }
        .empty-state svg { width: 48px; height: 48px; stroke: var(--muted); fill: none; stroke-width: 1.5; margin-bottom: 1rem; }
        .empty-state h3 { font-size: 1.1rem; margin-bottom: .5rem; }
        .empty-state p { color: var(--muted); font-size: .9rem; }

        @media (max-width: 600px) {
            .stats { grid-template-columns: 1fr 1fr; }
            .stat { border-right: none; border-bottom: 1px solid var(--border); }
            .profile-details { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="header">
            <a href="{{ route('consultation.index') }}" style="display:flex;align-items:center;gap:.7rem;text-decoration:none">
                <img src="{{ asset('image/logo.jpeg') }}" alt="IM'LUX" style="width:46px;height:46px;border-radius:12px;object-fit:cover;background:#fbf7ee;padding:3px">
                <span class="brand" style="font-family:'Cormorant Garamond',serif">PROJET IM'LUX</span>
            </a>
            <a href="{{ route('consultation.index') }}" class="back-btn">&#8592; Nouvelle recherche</a>
        </div>

        {{-- Profil souscripteur --}}
        <div class="profile-card">
            <div class="profile-header">
                <div class="avatar">
                    @if($souscripteur->photo)
                        <img src="{{ asset('storage/' . $souscripteur->photo) }}" alt="{{ $souscripteur->fullName() }}">
                    @else
                        {{ strtoupper(substr($souscripteur->first_name, 0, 1) . substr($souscripteur->last_name, 0, 1)) }}
                    @endif
                </div>
                <div class="profile-info">
                    <h2>{{ $souscripteur->fullName() }}</h2>
                    <span class="uid-tag">{{ $souscripteur->uid }}</span>
                </div>
            </div>
            <div class="profile-details">
                @if($souscripteur->email)
                    <div class="detail-item">
                        <div class="label">Email</div>
                        <div>{{ $souscripteur->email }}</div>
                    </div>
                @endif
                @if($souscripteur->phone)
                    <div class="detail-item">
                        <div class="label">Téléphone</div>
                        <div>{{ $souscripteur->phone }}</div>
                    </div>
                @endif
                @if($souscripteur->address)
                    <div class="detail-item">
                        <div class="label">Adresse</div>
                        <div>{{ $souscripteur->address }}</div>
                    </div>
                @endif
                <div class="detail-item">
                    <div class="label">Frais d'ouverture de dossier</div>
                    <div>
                        {{ number_format((float) $souscripteur->frais_ouverture, 0, ',', ' ') }} F
                        @if($souscripteur->frais_ouverture_payes)
                            <span class="badge badge-success" style="font-size:.66rem;margin-left:.3rem">Payés</span>
                            <a href="{{ URL::signedRoute('pdf.frais', $souscripteur) }}" target="_blank" style="color:var(--accent);font-size:.74rem;font-weight:600;text-decoration:none;margin-left:.3rem">Reçu PDF</a>
                        @else
                            <span class="badge badge-danger" style="font-size:.66rem;margin-left:.3rem">À régler</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Notifications récentes --}}
        @php $notifs = $souscripteur->appNotifications()->limit(8)->get(); @endphp
        @if($notifs->count() > 0)
            <div class="sousc-card" style="margin-bottom:1.5rem">
                <div class="sousc-header" style="border-bottom:1px solid var(--border)">
                    <div class="sousc-title">🔔 Mes notifications</div>
                </div>
                <div style="padding:.5rem 1.5rem 1rem">
                    @foreach($notifs as $n)
                        @php $ico = ['versement'=>'💰','frais'=>'📁','travaux'=>'🏗️','echeance'=>'⏰'][$n->type] ?? '🔔'; @endphp
                        <div style="display:flex;gap:.8rem;padding:.7rem 0;border-bottom:1px solid rgba(148,163,184,.12)">
                            <span style="font-size:1.2rem">{{ $ico }}</span>
                            <div style="flex:1;min-width:0">
                                <div style="font-weight:700;font-size:.9rem">{{ $n->title }}</div>
                                <div style="font-size:.84rem;color:var(--muted);margin-top:.1rem">{{ $n->body }}</div>
                                <div style="font-size:.72rem;color:var(--muted);margin-top:.2rem">{{ $n->created_at->format('d/m/Y H:i') }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Souscriptions --}}
        @forelse($souscriptions as $souscription)
            <div class="sousc-card">
                <div class="sousc-header">
                    <div>
                        <div class="sousc-title">{{ $souscription->programme->name }}</div>
                        <div class="sousc-subtitle">
                            Lot {{ $souscription->lot->reference }}
                            — {{ $souscription->lot->type_logement }}
                            @if($souscription->lot->surface)
                                ({{ number_format($souscription->lot->surface, 0, ',', ' ') }} m²)
                            @endif
                        </div>
                    </div>
                    @if($souscription->status === 'solde')
                        <span class="badge badge-success">Soldé</span>
                    @elseif($souscription->status === 'en_cours')
                        <span class="badge badge-warning">En cours</span>
                    @else
                        <span class="badge badge-danger">Annulé</span>
                    @endif
                </div>

                <div class="stats">
                    <div class="stat">
                        <div class="stat-label">Prix total</div>
                        <div class="stat-value">{{ number_format($souscription->total_price, 0, ',', ' ') }} F</div>
                    </div>
                    <div class="stat">
                        <div class="stat-label">Total versé</div>
                        <div class="stat-value success">{{ number_format($souscription->totalVerse(), 0, ',', ' ') }} F</div>
                    </div>
                    <div class="stat">
                        <div class="stat-label">Reste à payer</div>
                        <div class="stat-value danger">{{ number_format($souscription->resteAPayer(), 0, ',', ' ') }} F</div>
                    </div>
                    <div class="stat">
                        <div class="stat-label">{{ $souscription->echeanceLabel() }}</div>
                        <div class="stat-value accent">{{ number_format($souscription->mensualite, 0, ',', ' ') }} F</div>
                        <div style="font-size:.7rem;color:var(--muted);margin-top:.2rem">Rythme : {{ $souscription->rythmeLabel() }}</div>
                    </div>
                </div>

                <div class="progress-wrap">
                    <div class="progress-label">
                        <span>Progression des paiements</span>
                        <span>{{ number_format($souscription->progressPercent(), 1) }}%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: {{ min($souscription->progressPercent(), 100) }}%"></div>
                    </div>
                </div>

                {{-- Échéancier prévisionnel --}}
                @if($souscription->status !== 'solde' && count($souscription->echeancier()) > 0)
                    <div class="versements-section" style="border-bottom:1px solid var(--border)">
                        <div class="versements-title">Échéancier prévisionnel ({{ $souscription->rythmeLabel() }})</div>
                        <div class="table-wrap">
                            <table>
                                <thead><tr><th>N°</th><th>Échéance</th><th>Montant prévu</th></tr></thead>
                                <tbody>
                                    @foreach($souscription->echeancier() as $e)
                                        <tr>
                                            <td>{{ $e['n'] }}</td>
                                            <td>{{ $e['date']->format('d/m/Y') }}</td>
                                            <td style="font-weight:600">{{ number_format($e['montant'], 0, ',', ' ') }} F</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div style="font-size:.78rem;color:var(--muted);margin-top:.6rem">
                            Échéance recalculée (reste réparti sur {{ $souscription->echeancesRestantes() }} échéances restantes) :
                            <strong style="color:var(--accent)">{{ number_format($souscription->echeanceActuelle(), 0, ',', ' ') }} F</strong>
                        </div>
                    </div>
                @endif

                <div class="versements-section">
                    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:.5rem; margin-bottom:1rem;">
                        <div class="versements-title" style="margin-bottom:0">Historique des versements ({{ $souscription->versements->count() }})</div>
                        @if($souscription->versements->count() > 0)
                            <a href="{{ URL::signedRoute('pdf.attestation', $souscription) }}" target="_blank" style="padding:.4rem .8rem; border-radius:8px; background:rgba(200,162,74,.12); border:1px solid rgba(200,162,74,.25); color:var(--accent); font-size:.78rem; font-weight:700; text-decoration:none; transition:all .2s;">
                                &#128196; Attestation PDF
                            </a>
                        @endif
                    </div>
                    @if($souscription->versements->count() > 0)
                        <div class="table-wrap">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Montant</th>
                                        <th>Mode</th>
                                        <th>Référence</th>
                                        <th>Facture</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($souscription->versements->sortByDesc('payment_date') as $v)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($v->payment_date)->format('d/m/Y') }}</td>
                                            <td style="font-weight:700; color:var(--success)">{{ number_format($v->amount, 0, ',', ' ') }} F</td>
                                            <td><span class="method-badge">{{ ucfirst(str_replace('_', ' ', $v->payment_method)) }}</span></td>
                                            <td style="color:var(--muted)">{{ $v->reference ?? '—' }}</td>
                                            <td><a href="{{ URL::signedRoute('pdf.facture', $v) }}" target="_blank" style="color:var(--accent); font-size:.8rem; text-decoration:none; font-weight:600;">PDF</a></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="no-data">Aucun versement enregistré pour le moment.</div>
                    @endif
                </div>

                {{-- Avancement des travaux du programme --}}
                @php $etapes = $souscription->programme->etapes; $avg = $souscription->programme->avancementGlobal(); @endphp
                @if($etapes->count() > 0)
                    <div class="versements-section" style="border-top:1px solid var(--border)">
                        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
                            <div class="versements-title" style="margin-bottom:0">Avancement des travaux</div>
                            <span style="font-weight:800;color:var(--accent)">{{ $avg }}%</span>
                        </div>
                        <div class="progress-bar" style="margin-bottom:1.2rem"><div class="progress-fill" style="width:{{ $avg }}%"></div></div>
                        @foreach($etapes as $etape)
                            @php $imgs = $etape->images(); @endphp
                            <div style="padding:.8rem 0;border-bottom:1px solid rgba(148,163,184,.12)">
                                <div style="display:flex;justify-content:space-between;gap:.5rem;flex-wrap:wrap;align-items:center">
                                    <strong style="font-size:.92rem">{{ $etape->title }}
                                        @if(count($imgs))<span style="color:var(--muted);font-weight:500;font-size:.8rem">· {{ count($imgs) }} photo(s)</span>@endif
                                    </strong>
                                    <div style="display:flex;gap:.4rem;align-items:center">
                                        @if($etape->status==='termine')<span class="badge badge-success">Terminé</span>
                                        @elseif($etape->status==='en_cours')<span class="badge badge-warning">En cours</span>
                                        @else<span class="badge" style="background:var(--surface2);color:var(--muted)">À venir</span>@endif
                                        <span style="font-size:.8rem;color:var(--muted)">{{ $etape->progress }}%</span>
                                    </div>
                                </div>
                                @if($etape->description)<div style="font-size:.82rem;color:var(--muted);margin-top:.25rem">{{ $etape->description }}</div>@endif
                                <div class="progress-bar" style="height:6px;margin-top:.5rem;"><div class="progress-fill" style="width:{{ $etape->progress }}%"></div></div>
                                @if(count($imgs) > 0)
                                    <div style="display:flex;gap:.5rem;flex-wrap:wrap;margin-top:.7rem">
                                        @foreach($imgs as $img)
                                            <img src="{{ $img['url'] }}" alt="{{ $etape->title }}" onclick="cliImgLightbox('{{ $img['url'] }}')" style="width:92px;height:70px;border-radius:8px;object-fit:cover;cursor:pointer;border:1px solid var(--border)">
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @empty
            <div class="empty-state">
                <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                <h3>Aucune adhésion</h3>
                <p>Aucune adhésion n'est associée à votre compte pour le moment.</p>
            </div>
        @endforelse
    </div>

    {{-- Lightbox photos chantier --}}
    <div id="cliLightbox" onclick="this.style.display='none'" style="display:none;position:fixed;inset:0;z-index:60;background:rgba(8,16,12,.88);align-items:center;justify-content:center;padding:1.5rem;cursor:zoom-out;">
        <img id="cliLightboxImg" src="" alt="" style="max-width:95%;max-height:92%;border-radius:12px;box-shadow:0 30px 60px rgba(0,0,0,.5)">
    </div>
    <script>
        function cliImgLightbox(url){var b=document.getElementById('cliLightbox');document.getElementById('cliLightboxImg').src=url;b.style.display='flex';}
        document.addEventListener('keydown',function(e){if(e.key==='Escape')document.getElementById('cliLightbox').style.display='none';});
    </script>
</body>
</html>
