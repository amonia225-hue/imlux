<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lorny Conseils Management — Suivi d'adhésion</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('image/logo.jpeg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root{
            --gold:#c8a24a;--gold-light:#e8ce7e;--gold-dark:#9c7b2e;--emerald:#1f6b4c;
            --bg:#f6f4ee;--surface:#ffffff;--surface2:#f3f1e9;--border:#e6e0d2;
            --text:#1d2b22;--muted:#6b7770;--danger:#c2412f;--radius:16px;
        }
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:'Inter',sans-serif;min-height:100vh;display:flex;align-items:center;justify-content:center;
            background:radial-gradient(1100px 560px at 50% -10%,#eaf2ec 0%,var(--bg) 60%);color:var(--text);padding:1rem}
        .container{width:100%;max-width:540px;background:var(--surface);
            border:1px solid var(--border);border-radius:26px;padding:2.4rem 2rem;
            box-shadow:0 24px 60px rgba(31,107,76,.10),0 2px 8px rgba(0,0,0,.04);text-align:center}
        .logo-plate{width:118px;height:118px;margin:0 auto;padding:8px;background:#fff;border:1px solid var(--border);border-radius:22px;
            box-shadow:0 10px 26px rgba(200,162,74,.18)}
        .logo-plate img{width:100%;height:100%;object-fit:cover;border-radius:15px;display:block}
        .brand h1{font-family:'Cormorant Garamond',serif;font-size:2.1rem;font-weight:700;margin-top:1.2rem;
            background:linear-gradient(120deg,var(--gold-dark),var(--gold) 35%,var(--emerald));
            -webkit-background-clip:text;-webkit-text-fill-color:transparent}
        .brand p{color:var(--muted);font-size:.92rem;margin-top:.5rem;line-height:1.5}
        .search-form{margin-top:1.8rem;text-align:left}
        .search-form label{display:block;font-size:.76rem;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:.05em;margin-bottom:.5rem}
        .input-group{display:flex;gap:.5rem}
        .input-group input{flex:1;padding:.85rem 1rem;border:1px solid var(--border);border-radius:14px;background:var(--surface2);
            color:var(--text);font:inherit;font-size:.95rem;transition:border-color .2s,box-shadow .2s}
        .input-group input:focus{outline:none;border-color:var(--gold);box-shadow:0 0 0 3px rgba(200,162,74,.22);background:#fff}
        .input-group input::placeholder{color:#9aa49b}
        .btn-search{padding:.85rem 1.3rem;border:none;border-radius:14px;cursor:pointer;white-space:nowrap;
            background:linear-gradient(135deg,var(--gold-light),var(--gold) 55%,var(--gold-dark));color:#1a1206;
            font:inherit;font-weight:800;font-size:.95rem;transition:transform .15s,box-shadow .25s}
        .btn-search:hover{transform:translateY(-1px);box-shadow:0 8px 22px rgba(200,162,74,.4)}
        .error-msg{margin-top:1rem;background:rgba(194,65,47,.08);border:1px solid rgba(194,65,47,.28);
            border-radius:12px;padding:.75rem 1rem;font-size:.88rem;color:#a8341f;text-align:left}
        .help-text{margin-top:1.6rem;padding:1rem;background:rgba(31,107,76,.06);border:1px solid rgba(31,107,76,.16);
            border-radius:14px;font-size:.83rem;color:var(--muted);line-height:1.6;text-align:left}
        .help-text strong{color:var(--emerald)}
        .sep{display:flex;align-items:center;gap:.8rem;margin:1.6rem 0 .4rem;color:var(--muted);font-size:.8rem;font-weight:600;text-transform:uppercase;letter-spacing:.06em}
        .sep::before,.sep::after{content:'';flex:1;height:1px;background:var(--border)}
        .two{display:grid;grid-template-columns:1fr 1fr;gap:.5rem}
        .two .full{grid-column:1/-1}
        .field-block{margin-top:.6rem}
        .footer{margin-top:2rem;font-size:.82rem;color:var(--muted)}
        .footer a{color:var(--gold-dark);text-decoration:none;font-weight:700}
    </style>
</head>
<body>
    <div class="container">
        <div class="logo-plate"><img src="{{ asset('image/logo.jpeg') }}" alt="PROJET IM'LUX"></div>
        <div class="brand">
            <h1>PROJET IM'LUX</h1>
            <p>Consultez l'état de votre adhésion immobilière en temps réel</p>
        </div>

        <form class="search-form" action="{{ route('consultation.show') }}" method="GET">
            <label for="uid">Votre identifiant unique (UID)</label>
            <div class="input-group">
                <input type="text" id="uid" name="uid" placeholder="Ex: IMM-2026-0001" value="{{ request('uid') }}" required>
                <button type="submit" class="btn-search">Rechercher</button>
            </div>
        </form>

        @isset($error)
            <div class="error-msg">{{ $error }}</div>
        @endisset

        <div class="sep">ou</div>

        <form class="search-form" action="{{ route('consultation.phone') }}" method="POST">
            @csrf
            <label>Téléphone &amp; date de naissance</label>
            <div class="two">
                <input type="text" name="phone" placeholder="+225 07 ..." value="{{ old('phone') }}" required>
                <input type="date" name="date_naissance" value="{{ old('date_naissance') }}" required>
            </div>
            <div class="field-block">
                <button type="submit" class="btn-search" style="width:100%">Accéder à mon dossier</button>
            </div>
        </form>

        @isset($errorPhone)
            <div class="error-msg">{{ $errorPhone }}</div>
        @endisset
        @if($errors->any() && !isset($errorPhone))
            <div class="error-msg">{{ $errors->first() }}</div>
        @endif

        <div class="help-text">
            <strong>Comment ça marche ?</strong><br>
            Entrez votre identifiant unique (UID) fourni lors de votre adhésion pour consulter l'état de vos paiements, le solde restant et télécharger vos factures.
        </div>

        <div class="footer">
            <a href="{{ route('login') }}">Accès administrateur</a>
        </div>
    </div>
</body>
</html>
