<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <title>Connexion — Lorny Conseils Management</title>
    <link rel="icon" type="image/png" href="{{ asset('image/lorny.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,500&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root{
            --ink:#10243F;--navy:#13243F;--blue:#1E3A8C;--blue2:#3566D6;
            --orange:#ED8B1C;--orange2:#C9710E;--orange-soft:#F4B25E;
            --paper:#F6F5F0;--surface:#fff;--surface2:#FBFAF6;
            --line:#E8E5DC;--text:#1C2740;--muted:#6B7282;--danger:#c2412f;
            --serif:'Cormorant Garamond',serif;--sans:'Manrope',sans-serif;
        }
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:var(--sans);min-height:100vh;display:flex;color:var(--text);background:var(--paper);-webkit-font-smoothing:antialiased}
        a{text-decoration:none;color:inherit}

        /* ----- panneau gauche (visuel) ----- */
        .aside{flex:1.05;position:relative;overflow:hidden;background:var(--ink);color:#fff;
            display:flex;flex-direction:column;justify-content:space-between;padding:46px 50px}
        .aside::before{content:"";position:absolute;inset:0;
            background:url('{{ asset('image/slider1.jpeg') }}') center/cover no-repeat;opacity:.22}
        .aside::after{content:"";position:absolute;inset:0;
            background:radial-gradient(700px 480px at 85% 8%,rgba(53,102,214,.34),transparent 60%),
            radial-gradient(600px 420px at 6% 100%,rgba(237,139,28,.18),transparent 60%),
            linear-gradient(180deg,rgba(11,22,40,.55),rgba(11,22,40,.82))}
        .aside>*{position:relative;z-index:2}
        .brand{display:flex;align-items:center;gap:13px}
        .brand .chip{width:48px;height:48px;border-radius:13px;background:#fff;display:flex;align-items:center;justify-content:center;box-shadow:0 8px 22px rgba(0,0,0,.3)}
        .brand .chip img{width:38px;height:38px;object-fit:contain}
        .brand .bn{font-family:var(--serif);font-weight:600;font-size:22px;letter-spacing:.04em;line-height:1.02}
        .brand .bn small{display:block;font-family:var(--sans);font-weight:500;font-size:9.5px;letter-spacing:.3em;text-transform:uppercase;color:var(--orange-soft);margin-top:2px}
        .aside .pitch h2{font-family:var(--serif);font-weight:500;font-size:38px;line-height:1.12;letter-spacing:-.01em;max-width:14ch}
        .aside .pitch h2 em{font-style:italic;color:var(--orange-soft)}
        .aside .pitch p{color:rgba(255,255,255,.74);font-weight:300;font-size:15px;line-height:1.7;margin-top:18px;max-width:42ch}
        .aside .sig{font-family:var(--sans);font-size:11.5px;letter-spacing:.16em;text-transform:uppercase;color:rgba(255,255,255,.5)}

        /* ----- panneau droit (formulaire) ----- */
        .formside{flex:1;display:flex;align-items:center;justify-content:center;padding:40px 28px}
        .card{width:100%;max-width:400px}
        .card .eyebrow{display:inline-flex;align-items:center;gap:10px;font-size:11px;font-weight:600;letter-spacing:.24em;text-transform:uppercase;color:var(--orange2);margin-bottom:14px}
        .card .eyebrow::before{content:"";width:24px;height:1px;background:var(--orange)}
        .card h1{font-family:var(--serif);font-weight:600;font-size:34px;letter-spacing:-.01em;color:var(--navy)}
        .card .lead{color:var(--muted);font-size:14.5px;margin:8px 0 28px}
        label{display:block;font-size:11px;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:.08em;margin-bottom:7px}
        input{width:100%;padding:13px 15px;border:1px solid var(--line);border-radius:11px;background:var(--surface2);
            color:var(--text);font:inherit;font-size:15px;transition:border-color .2s,box-shadow .2s,background .2s}
        input:focus{outline:none;border-color:var(--blue2);box-shadow:0 0 0 3px rgba(53,102,214,.14);background:#fff}
        .field{margin-bottom:16px}
        .btn{width:100%;padding:14px;border:none;border-radius:12px;font:inherit;font-weight:600;font-size:15px;cursor:pointer;
            background:var(--orange);color:#fff;transition:background .2s,transform .15s,box-shadow .25s;margin-top:6px;
            box-shadow:0 12px 26px -10px rgba(237,139,28,.6)}
        .btn:hover{background:var(--orange2);transform:translateY(-1px)}
        .errors{background:rgba(194,65,47,.07);border:1px solid rgba(194,65,47,.26);border-radius:12px;padding:11px 14px;margin-bottom:18px;font-size:13.5px;color:#a8341f}
        .errors ul{margin:0;padding-left:18px;list-style:disc}
        .footer{text-align:center;margin-top:24px;font-size:13.5px;color:var(--muted);border-top:1px solid var(--line);padding-top:20px}
        .footer a{color:var(--blue);font-weight:600}
        .footer a:hover{color:var(--orange2)}
        .back{display:inline-block;margin-top:18px;font-size:13px;color:var(--muted)}
        .back:hover{color:var(--orange2)}

        @media(max-width:820px){
            body{flex-direction:column}
            .aside{flex:none;padding:34px 30px;min-height:200px}
            .aside .pitch{display:none}
            .formside{flex:1;padding:34px 24px}
        }
    </style>
</head>
<body>
    <div class="aside">
        <a class="brand" href="{{ route('home') }}">
            <span class="chip"><img src="{{ asset('image/lorny.png') }}" alt="Lorny Conseils Management"></span>
            <span class="bn">Lorny<small>Conseils · Management</small></span>
        </a>
        <div class="pitch">
            <h2>Votre patrimoine, <em>suivi de près.</em></h2>
            <p>Accédez à votre espace pour retrouver vos dossiers, vos échéances et l'avancement de vos projets.</p>
        </div>
        <div class="sig">Lorny Conseils Management — Abidjan</div>
    </div>

    <div class="formside">
        <div class="card">
            <div class="eyebrow">Bienvenue</div>
            <h1>Connexion</h1>
            <p class="lead">Saisissez vos identifiants pour accéder à votre espace.</p>

            @if ($errors->any())
                <div class="errors">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login.attempt') }}">
                @csrf
                <div class="field">
                    <label for="email">Adresse email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="vous@email.com">
                </div>
                <div class="field">
                    <label for="password">Mot de passe</label>
                    <input id="password" name="password" type="password" required autocomplete="current-password" placeholder="••••••••">
                </div>
                <button class="btn" type="submit">Se connecter</button>
            </form>

            <div class="footer">
                Vous êtes adhérent ? <a href="{{ route('consultation.index') }}">Accéder à mon suivi</a>
            </div>
            <a class="back" href="{{ route('home') }}">← Retour à l'accueil</a>
        </div>
    </div>
</body>
</html>
