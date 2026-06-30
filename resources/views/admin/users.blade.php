<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex,nofollow">
<title>Utilisateurs — Administration Lorny</title>
<link rel="icon" type="image/png" href="{{ asset('image/lorny.png') }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<style>
:root{--ink:#08152B;--blue:#1E3A8C;--blue2:#3566D6;--orange:#ED8B1C;--orange2:#C9710E;--steel:#6B7A93;--line:#E5E9F2;--paper:#EEF1F7;--danger:#c2412f;--ok:#256b45;--disp:'Space Grotesk',sans-serif;--body:'Inter',sans-serif}
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:var(--body);background:var(--paper);color:var(--ink);line-height:1.6;padding:28px}
a{text-decoration:none;color:inherit}
.wrap{max-width:1000px;margin:0 auto}
.top{display:flex;align-items:center;justify-content:space-between;gap:16px;margin-bottom:8px;flex-wrap:wrap}
.top h1{font-family:var(--disp);font-weight:700;font-size:26px;letter-spacing:-.02em}
.top h1 em{font-style:normal;color:var(--orange2)}
.back{font-size:13.5px;color:var(--blue);font-weight:600}
.sub{color:var(--steel);font-size:13.5px;margin-bottom:22px}
.flash{border-radius:10px;padding:12px 16px;margin-bottom:16px;font-size:14px}
.flash.ok{background:#eef7f1;border:1px solid #c4e3d1;color:var(--ok)}
.flash.err{background:#fdecec;border:1px solid #f5c2c2;color:#a23131}
.grid{display:grid;grid-template-columns:1.4fr 1fr;gap:22px;align-items:start}
.panel{background:#fff;border:1px solid var(--line);border-radius:14px;padding:22px 24px;box-shadow:0 1px 0 rgba(8,21,43,.04)}
.panel h2{font-family:var(--disp);font-weight:600;font-size:16px;margin-bottom:16px}
.u{display:flex;justify-content:space-between;align-items:center;gap:14px;padding:15px 0;border-bottom:1px solid var(--line)}
.u:last-child{border-bottom:none}
.u .nm{font-family:var(--disp);font-weight:600;font-size:15.5px}
.u .em{font-size:13px;color:var(--steel)}
.u .role{display:inline-block;font-size:10.5px;font-weight:700;letter-spacing:.05em;text-transform:uppercase;padding:3px 9px;border-radius:999px;margin-top:5px}
.role.super{background:#fff4e6;color:var(--orange2);border:1px solid #f3d3a3}
.role.adm{background:#eef2fb;color:var(--blue);border:1px solid #d6e0f5}
.role.you{background:#e9f7ef;color:var(--ok);border:1px solid #c4e3d1;margin-left:6px}
.btn{border:none;border-radius:8px;padding:8px 15px;font:inherit;font-size:13px;font-weight:600;cursor:pointer;transition:.18s}
.btn-del{background:#fff;border:1px solid #f0c4c4;color:#b33}.btn-del:hover{background:#fdecec}
.lock{font-size:12px;color:var(--steel);display:inline-flex;align-items:center;gap:5px}
label{display:block;font-size:11px;letter-spacing:.06em;text-transform:uppercase;color:var(--steel);font-weight:600;margin-bottom:6px}
.field{margin-bottom:14px}
input{width:100%;padding:11px 13px;border:1px solid var(--line);border-radius:10px;background:#F7F9FD;font:inherit;font-size:14px;color:var(--ink);transition:.18s}
input:focus{outline:none;border-color:var(--blue2);box-shadow:0 0 0 3px rgba(53,102,214,.14);background:#fff}
.submit{width:100%;padding:12px;border:none;border-radius:10px;background:var(--orange);color:#fff;font:inherit;font-weight:600;font-size:14.5px;cursor:pointer;transition:.18s;margin-top:4px}
.submit:hover{background:var(--orange2)}
.hint{font-size:12px;color:var(--steel);margin-top:12px;line-height:1.5}
.errs{background:#fdecec;border:1px solid #f5c2c2;color:#a23131;border-radius:10px;padding:10px 13px;margin-bottom:14px;font-size:13px}
.errs ul{margin:0;padding-left:16px}
@media(max-width:760px){.grid{grid-template-columns:1fr}}
</style>
</head>
<body>
<div class="wrap">
  <div class="top">
    <h1>👤 Gestion des <em>utilisateurs</em></h1>
    <div style="font-size:13px;color:var(--steel)">{{ $users->count() }} compte(s)</div>
  </div>
  <a class="back" href="{{ route('admin.dashboard') }}">← Retour au tableau de bord</a>
  <p class="sub">Créez et gérez les comptes administrateurs qui accèdent au tableau de bord.</p>

  @if(session('success'))<div class="flash ok">{{ session('success') }}</div>@endif
  @if(session('error'))<div class="flash err">{{ session('error') }}</div>@endif

  <div class="grid">
    {{-- Liste --}}
    <div class="panel">
      <h2>Comptes existants</h2>
      @foreach($users as $u)
        <div class="u">
          <div>
            <div class="nm">{{ $u->name }}
              @if($u->id === auth()->id())<span class="role you">vous</span>@endif
            </div>
            <div class="em">{{ $u->email }}</div>
            @if($u->is_super_admin)
              <span class="role super">★ Super administrateur</span>
            @else
              <span class="role adm">Administrateur</span>
            @endif
          </div>
          <div>
            @if($u->is_super_admin)
              <span class="lock">🔒 Compte protégé</span>
            @elseif($u->id === auth()->id())
              <span class="lock">—</span>
            @else
              <form method="POST" action="{{ route('users.destroy', $u) }}" onsubmit="return confirm('Supprimer le compte {{ $u->email }} ?')">@csrf
                <button class="btn btn-del" type="submit">Supprimer</button>
              </form>
            @endif
          </div>
        </div>
      @endforeach
    </div>

    {{-- Création --}}
    <div class="panel">
      <h2>Nouvel administrateur</h2>
      @if($errors->any())
        <div class="errs"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
      @endif
      <form method="POST" action="{{ route('users.store') }}">
        @csrf
        <div class="field"><label>Nom complet</label><input name="name" value="{{ old('name') }}" required></div>
        <div class="field"><label>Adresse email</label><input name="email" type="email" value="{{ old('email') }}" required></div>
        <div class="field"><label>Mot de passe</label><input name="password" type="password" required></div>
        <div class="field"><label>Confirmer le mot de passe</label><input name="password_confirmation" type="password" required></div>
        <button class="submit" type="submit">Créer le compte</button>
      </form>
      <p class="hint">Le nouveau compte a accès au tableau de bord (administrateur). Le statut <strong>super administrateur</strong> est protégé et ne s'attribue pas depuis cette page.</p>
    </div>
  </div>
</div>
</body>
</html>
