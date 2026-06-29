<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PROJET IM'LUX — Connexion</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('image/logo.jpeg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root{
            --gold:#c8a24a;--gold-light:#e8ce7e;--gold-dark:#9c7b2e;--emerald:#1f6b4c;
            --bg:#f6f4ee;--surface:#ffffff;--surface2:#f3f1e9;
            --border:#e6e0d2;--text:#1d2b22;--muted:#6b7770;--danger:#c2412f;
        }
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:'Inter',sans-serif;min-height:100vh;display:flex;align-items:center;justify-content:center;
            background:radial-gradient(1100px 560px at 50% -10%,#eaf2ec 0%,var(--bg) 60%);color:var(--text);padding:1rem}
        .login-card{width:100%;max-width:430px;background:var(--surface);
            border:1px solid var(--border);border-radius:26px;padding:2.4rem 2rem;
            box-shadow:0 24px 60px rgba(31,107,76,.10),0 2px 8px rgba(0,0,0,.04)}
        .logo-plate{width:128px;height:128px;margin:0 auto;padding:8px;background:#fff;border:1px solid var(--border);border-radius:24px;
            box-shadow:0 10px 26px rgba(200,162,74,.18)}
        .logo-plate img{width:100%;height:100%;object-fit:cover;border-radius:16px;display:block}
        .brand{text-align:center;margin:1.4rem 0 1.6rem}
        .brand h1{font-family:'Cormorant Garamond',serif;font-size:2rem;font-weight:700;letter-spacing:.01em;
            background:linear-gradient(120deg,var(--gold-dark),var(--gold) 35%,var(--emerald));
            -webkit-background-clip:text;-webkit-text-fill-color:transparent}
        .brand p{color:var(--muted);font-size:.9rem;margin-top:.3rem}
        label{display:block;font-size:.76rem;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:.05em;margin-bottom:.4rem}
        input{width:100%;padding:.8rem 1rem;border:1px solid var(--border);border-radius:14px;background:var(--surface2);
            color:var(--text);font:inherit;font-size:.95rem;transition:border-color .2s,box-shadow .2s}
        input:focus{outline:none;border-color:var(--gold);box-shadow:0 0 0 3px rgba(200,162,74,.22);background:#fff}
        .field{margin-bottom:1.1rem}
        .btn{width:100%;padding:.9rem;border:none;border-radius:14px;font:inherit;font-weight:800;font-size:.98rem;cursor:pointer;
            background:linear-gradient(135deg,var(--gold-light),var(--gold) 55%,var(--gold-dark));color:#1a1206;
            transition:transform .15s,box-shadow .25s;margin-top:.4rem;letter-spacing:.02em}
        .btn:hover{transform:translateY(-1px);box-shadow:0 10px 24px rgba(200,162,74,.4)}
        .errors{background:rgba(194,65,47,.08);border:1px solid rgba(194,65,47,.28);border-radius:12px;padding:.65rem .85rem;margin-bottom:1rem;font-size:.85rem;color:#a8341f}
        .errors ul{margin:0;padding-left:1rem;list-style:disc}
        .footer{text-align:center;margin-top:1.6rem;font-size:.82rem;color:var(--muted)}
        .footer a{color:var(--gold-dark);text-decoration:none;font-weight:700}
        .footer a:hover{color:var(--emerald)}
    </style>
</head>
<body>
    <div class="login-card">
        <div class="logo-plate"><img src="{{ asset('image/logo.jpeg') }}" alt="PROJET IM'LUX"></div>
        <div class="brand">
            <h1>PROJET IM'LUX</h1>
            <p>Espace administration · Logements &amp; Gestion</p>
        </div>

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
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus placeholder="admin@immo-gest.ci">
            </div>
            <div class="field">
                <label for="password">Mot de passe</label>
                <input id="password" name="password" type="password" required placeholder="••••••••">
            </div>
            <button class="btn" type="submit">Se connecter</button>
        </form>

        <div class="footer">
            <a href="{{ route('consultation.index') }}">Espace client → Consulter mon suivi</a>
        </div>
    </div>
</body>
</html>
