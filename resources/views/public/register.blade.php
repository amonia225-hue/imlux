@php $logo = asset('image/lorny.png'); @endphp
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Créer un compte — Lorny Conseils Management</title>
<link rel="icon" type="image/png" href="{{ $logo }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
<style>
:root{--ink:#08152B;--blue:#1E3A8C;--blue2:#3566D6;--orange:#ED8B1C;--orange2:#C9710E;--steel:#6B7A93;--line:#E5E9F2;--paper:#F5F7FB;--disp:'Space Grotesk',sans-serif;--body:'Inter',sans-serif;--mono:'Space Mono',monospace}
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:var(--body);color:var(--ink);background:var(--paper);min-height:100vh;line-height:1.6}
a{text-decoration:none;color:inherit}
.split{display:grid;grid-template-columns:1fr 1.1fr;min-height:100vh}
.aside{background:var(--ink);color:#fff;padding:48px 52px;position:relative;overflow:hidden;display:flex;flex-direction:column;justify-content:space-between}
.aside::before{content:"";position:absolute;inset:0;background:radial-gradient(700px 480px at 80% 10%,rgba(53,102,214,.28),transparent 60%),radial-gradient(600px 420px at 10% 100%,rgba(237,139,28,.12),transparent 60%)}
.brand{display:flex;align-items:center;gap:11px;position:relative}
.brand .chip{height:44px;width:44px;border-radius:11px;background:#fff;display:flex;align-items:center;justify-content:center}
.brand .chip img{height:36px;width:36px;object-fit:contain}
.brand .bn{font-family:var(--disp);font-weight:700;font-size:15px;line-height:1.05}
.brand .bn small{display:block;font-family:var(--mono);font-weight:400;font-size:10px;letter-spacing:.2em;color:var(--orange)}
.aside h1{font-family:var(--disp);font-weight:700;font-size:38px;line-height:1.08;letter-spacing:-.02em;margin:24px 0 0;position:relative;max-width:16ch}
.aside .hl{color:var(--orange)}
.points{position:relative;display:grid;gap:16px;margin-top:8px}
.points div{display:flex;gap:12px;align-items:flex-start;color:rgba(255,255,255,.82);font-size:14.5px}
.points .ic{flex:0 0 auto;width:30px;height:30px;border-radius:9px;background:rgba(255,255,255,.08);display:flex;align-items:center;justify-content:center;color:var(--orange);font-family:var(--mono);font-size:13px}
.aside .foot{position:relative;font-family:var(--mono);font-size:11px;color:rgba(255,255,255,.5);letter-spacing:.1em}
.formside{display:flex;align-items:center;justify-content:center;padding:40px}
.card{width:100%;max-width:560px}
.eb{font-family:var(--mono);font-size:12px;letter-spacing:.16em;text-transform:uppercase;color:var(--steel)}
.card h2{font-family:var(--disp);font-weight:700;font-size:30px;letter-spacing:-.02em;margin:8px 0 4px}
.card .lead{color:var(--steel);font-size:14.5px;margin-bottom:24px}
.grid{display:grid;grid-template-columns:1fr 1fr;gap:14px}
label{display:block;font-family:var(--mono);font-size:11px;letter-spacing:.06em;text-transform:uppercase;color:var(--steel);margin-bottom:6px}
.field{margin-bottom:14px}.field.full{grid-column:1/-1}
input{width:100%;padding:12px 14px;border:1px solid var(--line);border-radius:11px;background:#fff;font:inherit;font-size:14.5px;color:var(--ink);transition:.18s}
input:focus{outline:none;border-color:var(--blue2);box-shadow:0 0 0 3px rgba(53,102,214,.14)}
.btn{width:100%;padding:14px;border:none;border-radius:12px;font:inherit;font-weight:600;font-size:15px;cursor:pointer;background:var(--orange);color:#2a1602;transition:.18s;margin-top:6px}
.btn:hover{background:#ffa436}
.alt{text-align:center;margin-top:18px;font-size:14px;color:var(--steel)}
.alt a{color:var(--blue);font-weight:600}
.errors{background:#fdecec;border:1px solid #f5c2c2;color:#a23131;border-radius:12px;padding:12px 14px;margin-bottom:18px;font-size:13.5px}
.errors ul{margin:0;padding-left:18px}
.ok{background:#eaf6ef;border:1px solid #bfe3cd;border-radius:16px;padding:26px;text-align:center}
.ok .ic{width:56px;height:56px;border-radius:50%;background:var(--orange);color:#2a1602;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;font-size:28px;font-family:var(--disp)}
.ok h3{font-family:var(--disp);font-weight:700;font-size:22px;margin-bottom:8px}
.ok p{color:var(--steel);font-size:14.5px;max-width:42ch;margin:0 auto}
.back{display:inline-block;margin-bottom:22px;font-size:13.5px;color:var(--steel)}
.back:hover{color:var(--blue)}
@media(max-width:860px){.split{grid-template-columns:1fr}.aside{display:none}.grid{grid-template-columns:1fr}}
</style>
</head>
<body>
<div class="split">
  <div class="aside">
    <a class="brand" href="/"><span class="chip"><img src="{{ $logo }}" alt="LCM"></span><span class="bn">LORNY CONSEILS<small>MANAGEMENT · LCM</small></span></a>
    <div>
      <h1>Rejoignez le bureau d'études,<br><span class="hl">en quelques minutes.</span></h1>
      <div class="points" style="margin-top:28px">
        <div><span class="ic">01</span><div>Créez votre compte et renseignez votre profil.</div></div>
        <div><span class="ic">02</span><div>Un conseiller valide votre dossier sous peu.</div></div>
        <div><span class="ic">03</span><div>Accédez à votre espace : programmes, échéanciers, travaux.</div></div>
      </div>
    </div>
    <div class="foot">© {{ date('Y') }} LORNY CONSEILS MANAGEMENT — ABIDJAN</div>
  </div>

  <div class="formside">
    <div class="card">
      <a class="back" href="/">← Retour à l'accueil</a>
      @if(session('registered'))
        <div class="ok">
          <div class="ic">✓</div>
          <h3>Demande envoyée</h3>
          <p>Votre compte a bien été créé. Un conseiller du bureau d'études va le <strong>valider</strong> — vous serez notifié dès l'activation de votre espace membre.</p>
          <a class="btn" href="/" style="display:inline-block;width:auto;padding:12px 24px;margin-top:18px">Revenir à l'accueil</a>
        </div>
      @else
        <div class="eb">Adhésion</div>
        <h2>Créer mon compte</h2>
        <p class="lead">Renseignez vos informations. La validation se fait par le bureau d'études.</p>

        @if($errors->any())
          <div class="errors"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif

        <form method="POST" action="{{ route('register.store') }}">
          @csrf
          <div class="grid">
            <div class="field"><label>Prénom</label><input name="first_name" value="{{ old('first_name') }}" required></div>
            <div class="field"><label>Nom</label><input name="last_name" value="{{ old('last_name') }}" required></div>
            <div class="field"><label>Email</label><input name="email" type="email" value="{{ old('email') }}" required></div>
            <div class="field"><label>Téléphone</label><input name="phone" value="{{ old('phone') }}" placeholder="+225 ..." required></div>
            <div class="field"><label>Date de naissance</label><input name="date_naissance" type="date" value="{{ old('date_naissance') }}" required></div>
            <div class="field"><label>Adresse</label><input name="address" value="{{ old('address') }}" placeholder="Quartier, ville"></div>
            <div class="field"><label>Mot de passe</label><input name="password" type="password" required></div>
            <div class="field"><label>Confirmer</label><input name="password_confirmation" type="password" required></div>
          </div>
          <button class="btn" type="submit">Créer mon compte</button>
        </form>
        <div class="alt">Déjà adhérent ? <a href="{{ route('consultation.index') }}">Accéder à mon suivi</a></div>
      @endif
    </div>
  </div>
</div>
</body>
</html>
