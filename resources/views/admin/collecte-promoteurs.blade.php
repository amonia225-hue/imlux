<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Collecte promoteurs — Lorny Conseils Management</title>
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
        body{font-family:'Jost',sans-serif;background:var(--bg);color:var(--text);min-height:100vh}
        .page{max-width:1200px;margin:0 auto;padding:1rem}
        .topbar{display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem;margin-bottom:1rem}
        .topbar a.back{color:var(--muted);text-decoration:none;font-weight:600;font-size:.88rem;border:1px solid var(--border);padding:.5rem .9rem;border-radius:var(--radius-sm)}
        .topbar a.back:hover{color:var(--text);border-color:var(--accent)}
        .hero{background:linear-gradient(120deg,#0B1426 0%,#16329B 68%,#1E40AF 100%);border:1px solid #1E40AF;border-left:5px solid var(--accent);border-radius:22px;padding:1.4rem 1.6rem;box-shadow:0 18px 36px rgba(11,20,38,.35);color:#fff}
        .hero h1{font-family:'Playfair Display',serif;font-size:1.9rem;font-weight:700}
        .hero p{color:rgba(255,255,255,.8);margin-top:.3rem;font-size:.92rem;max-width:70ch;line-height:1.6}
        .panel{background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);padding:1.2rem;margin-top:1rem;box-shadow:0 4px 18px rgba(30,64,175,.07)}
        .panel h3{font-size:1.1rem;font-weight:700;margin-bottom:.3rem}
        .panel .subtitle{color:var(--muted);font-size:.85rem;margin-bottom:1rem}
        label{display:block;font-size:.74rem;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:.04em;margin-bottom:.35rem}
        input,select,textarea{width:100%;padding:.7rem .8rem;border:1px solid var(--border);border-radius:var(--radius-sm);background:var(--surface2);color:var(--text);font:inherit;font-size:.9rem}
        input:focus,select:focus,textarea:focus{outline:none;border-color:var(--accent);box-shadow:0 0 0 3px rgba(30,64,175,.15)}
        .form-grid{display:grid;gap:.8rem;grid-template-columns:1fr}
        @media(min-width:720px){.form-grid{grid-template-columns:repeat(3,1fr)}}
        .btn{border:none;border-radius:var(--radius-sm);padding:.7rem 1.1rem;font:inherit;font-weight:800;color:#fff;background:var(--accent);cursor:pointer}
        .icon-btn{border:1px solid var(--border);background:var(--surface2);color:var(--muted);border-radius:8px;padding:.35rem .7rem;font:inherit;font-size:.78rem;font-weight:600;cursor:pointer;text-decoration:none;display:inline-block}
        .icon-btn:hover{color:var(--text);border-color:var(--accent)}
        .icon-btn.danger:hover{color:var(--danger);border-color:rgba(248,113,113,.4)}
        .flash{background:rgba(52,211,153,.1);border:1px solid rgba(52,211,153,.25);color:var(--success);border-radius:var(--radius-sm);padding:.65rem .8rem;font-size:.88rem;margin-bottom:.8rem}
        .err{background:rgba(194,65,47,.08);border:1px solid rgba(194,65,47,.25);color:var(--danger);border-radius:var(--radius-sm);padding:.65rem .8rem;font-size:.88rem;margin-bottom:.8rem}
        table{width:100%;border-collapse:collapse;font-size:.88rem}
        th{text-align:left;font-size:.72rem;text-transform:uppercase;letter-spacing:.05em;color:var(--muted);padding:.5rem .4rem;border-bottom:1px solid var(--border)}
        td{padding:.7rem .4rem;border-bottom:1px solid var(--border);vertical-align:top}
        .lien{display:flex;gap:.4rem;align-items:center}
        .lien input{font-family:ui-monospace,monospace;font-size:.75rem;background:#fff}
        .tag{display:inline-block;font-size:.72rem;font-weight:700;padding:.2rem .6rem;border-radius:999px}
        .tag.att{background:rgba(185,131,31,.12);color:var(--warning)}
        .tag.ok{background:rgba(31,138,90,.12);color:var(--success)}
        .tag.no{background:rgba(194,65,47,.1);color:var(--danger)}
        .tag.rev{background:#eee;color:var(--muted)}
    </style>
</head>
<body>
<div class="page">
    <div class="topbar">
        <a class="back" href="{{ route('admin.dashboard') }}">← Tableau de bord</a>
    </div>

    <div class="hero">
        <h1>Collecte auprès des promoteurs</h1>
        <p>
            Créez un lien par promoteur et transmettez-le. Il remplit le formulaire sans avoir de compte,
            joint ses plans et ses photos, puis vous transmet le tout. Chaque lien est nominatif : vous
            savez toujours qui a déposé quoi, et vous pouvez le révoquer.
        </p>
    </div>

    @if (session('ok'))<div class="flash" style="margin-top:1rem">{{ session('ok') }}</div>@endif
    @if ($errors->any())<div class="err" style="margin-top:1rem">{{ $errors->first() }}</div>@endif

    <div class="panel">
        <h3>Nouveau lien promoteur</h3>
        <p class="subtitle">Le lien expire automatiquement — au-delà, il faut en générer un nouveau.</p>

        <form method="POST" action="{{ route('admin.collecte.invitations.store') }}">
            @csrf
            <div class="form-grid">
                <div><label for="promoteur">Promoteur / société *</label><input id="promoteur" name="promoteur" required></div>
                <div><label for="contact">Personne à contacter</label><input id="contact" name="contact"></div>
                <div><label for="telephone">Téléphone</label><input id="telephone" name="telephone"></div>
                <div><label for="email">E-mail</label><input id="email" type="email" name="email"></div>
                <div><label for="jours_validite">Validité (jours)</label><input id="jours_validite" type="number" name="jours_validite" value="60" min="1" max="365"></div>
                <div><label for="note">Note interne</label><input id="note" name="note"></div>
            </div>
            <button type="submit" class="btn" style="margin-top:.9rem">Générer le lien</button>
        </form>
    </div>

    <div class="panel">
        <h3>Liens actifs</h3>
        <p class="subtitle">Copiez le lien et envoyez-le par e-mail ou WhatsApp.</p>

        @if ($invitations->isEmpty())
            <p style="color:var(--muted);font-size:.9rem">Aucun lien créé pour le moment.</p>
        @else
            <table>
                <thead>
                    <tr><th>Promoteur</th><th>Lien à transmettre</th><th>Validité</th><th>Dépôts</th><th></th></tr>
                </thead>
                <tbody>
                @foreach ($invitations as $invitation)
                    @php $url = route('promoteur.depot', $invitation->token); @endphp
                    <tr>
                        <td>
                            <strong>{{ $invitation->promoteur }}</strong>
                            @if ($invitation->contact)<br><span style="color:var(--muted);font-size:.8rem">{{ $invitation->contact }}</span>@endif
                            @if ($invitation->telephone)<br><span style="color:var(--muted);font-size:.8rem">{{ $invitation->telephone }}</span>@endif
                        </td>
                        <td style="min-width:280px">
                            <div class="lien">
                                <input type="text" readonly value="{{ $url }}" id="lien-{{ $invitation->id }}">
                                <button type="button" class="icon-btn" onclick="copier('lien-{{ $invitation->id }}', this)">Copier</button>
                            </div>
                        </td>
                        <td>
                            @if ($invitation->revoquee_le)
                                <span class="tag rev">révoqué</span>
                            @elseif ($invitation->expire_le && $invitation->expire_le->isPast())
                                <span class="tag no">expiré</span>
                            @else
                                <span class="tag ok">actif</span><br>
                                <span style="color:var(--muted);font-size:.78rem">jusqu'au {{ $invitation->expire_le?->format('d/m/Y') ?? '—' }}</span>
                            @endif
                        </td>
                        <td>{{ $invitation->soumissions_count }}</td>
                        <td style="text-align:right">
                            @unless ($invitation->revoquee_le)
                                <form method="POST" action="{{ route('admin.collecte.invitations.revoquer', $invitation) }}"
                                      onsubmit="return confirm('Révoquer ce lien ? Le promoteur ne pourra plus déposer.')">
                                    @csrf
                                    <button type="submit" class="icon-btn danger">Révoquer</button>
                                </form>
                            @endunless
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <div class="panel">
        <h3>Dépôts reçus @if ($aExaminer > 0)<span class="tag att" style="margin-left:.4rem">{{ $aExaminer }} à examiner</span>@endif</h3>
        <p class="subtitle">Les brouillons en cours de saisie n'apparaissent pas ici.</p>

        @if ($soumissions->isEmpty())
            <p style="color:var(--muted);font-size:.9rem">Aucun dépôt transmis pour le moment.</p>
        @else
            <table>
                <thead>
                    <tr><th>Promoteur</th><th>Biens</th><th>Transmis le</th><th>Statut</th><th></th></tr>
                </thead>
                <tbody>
                @foreach ($soumissions as $soumission)
                    <tr>
                        <td>
                            <strong>{{ $soumission->promoteur }}</strong>
                            @if ($soumission->telephone)<br><span style="color:var(--muted);font-size:.8rem">{{ $soumission->telephone }}</span>@endif
                        </td>
                        <td>{{ $soumission->biens->count() }}</td>
                        <td>{{ $soumission->soumise_le?->format('d/m/Y H:i') ?? '—' }}</td>
                        <td>
                            <span class="tag {{ $soumission->statut === 'validee' ? 'ok' : ($soumission->statut === 'rejetee' ? 'no' : 'att') }}">
                                {{ $soumission->statutLabel() }}
                            </span>
                        </td>
                        <td style="text-align:right">
                            <a class="icon-btn" href="{{ route('admin.collecte.show', $soumission) }}">Examiner</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>

<script>
    function copier(id, bouton) {
        const champ = document.getElementById(id);
        champ.select();
        navigator.clipboard.writeText(champ.value).then(() => {
            const initial = bouton.textContent;
            bouton.textContent = 'Copié ✓';
            setTimeout(() => { bouton.textContent = initial; }, 1600);
        });
    }
</script>
</body>
</html>
