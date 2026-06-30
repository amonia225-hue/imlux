<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Messages — Administration Lorny</title>
<link rel="icon" type="image/png" href="{{ asset('image/lorny.png') }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<style>
:root{--ink:#08152B;--blue:#1E3A8C;--blue2:#3566D6;--orange:#ED8B1C;--orange2:#C9710E;--steel:#6B7A93;--line:#E5E9F2;--paper:#EEF1F7;--disp:'Space Grotesk',sans-serif;--body:'Inter',sans-serif}
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:var(--body);background:var(--paper);color:var(--ink);line-height:1.6;padding:28px}
a{text-decoration:none;color:inherit}
.wrap{max-width:1040px;margin:0 auto}
.top{display:flex;align-items:center;justify-content:space-between;gap:16px;margin-bottom:24px;flex-wrap:wrap}
.top h1{font-family:var(--disp);font-weight:700;font-size:26px;letter-spacing:-.02em}
.top h1 em{font-style:normal;color:var(--orange2)}
.back{font-size:13.5px;color:var(--blue);font-weight:600}
.flash{background:#eef7f1;border:1px solid #c4e3d1;color:#256b45;border-radius:10px;padding:12px 16px;margin-bottom:18px;font-size:14px}
.msg{background:#fff;border:1px solid var(--line);border-radius:12px;padding:20px 22px;margin-bottom:14px;box-shadow:0 1px 0 rgba(8,21,43,.04)}
.msg.unread{border-left:3px solid var(--orange)}
.msg .h{display:flex;justify-content:space-between;align-items:baseline;gap:12px;flex-wrap:wrap}
.msg .nm{font-family:var(--disp);font-weight:600;font-size:17px}
.msg .nm .badge{background:var(--orange);color:#fff;font-size:10px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;padding:2px 8px;border-radius:999px;margin-left:8px;vertical-align:middle}
.msg .dt{font-size:12.5px;color:var(--steel)}
.msg .meta{display:flex;gap:18px;flex-wrap:wrap;font-size:13.5px;color:var(--steel);margin:6px 0 12px}
.msg .meta a{color:var(--blue);font-weight:600}
.msg .sub{font-weight:600;font-size:14px;margin-bottom:4px}
.msg .body{font-size:14.5px;color:#33405c;white-space:pre-wrap}
.msg .acts{display:flex;gap:10px;margin-top:14px}
.btn{border:none;border-radius:8px;padding:8px 16px;font:inherit;font-size:13px;font-weight:600;cursor:pointer;transition:.18s}
.btn-read{background:var(--blue);color:#fff}.btn-read:hover{background:var(--blue2)}
.btn-del{background:#fff;border:1px solid #f0c4c4;color:#b33}.btn-del:hover{background:#fdecec}
.empty{background:#fff;border:1px solid var(--line);border-radius:12px;padding:60px 20px;text-align:center;color:var(--steel)}
.empty h3{font-family:var(--disp);color:var(--ink);font-size:20px;margin-bottom:6px}
.pag{margin-top:18px}
.pag a,.pag span{display:inline-block;padding:6px 11px;border-radius:8px;font-size:13px;margin-right:4px;color:var(--steel)}
.pag a{background:#fff;border:1px solid var(--line);color:var(--blue)}
</style>
</head>
<body>
<div class="wrap">
  <div class="top">
    <div>
      <h1>📨 Messages de <em>contact</em></h1>
      <a class="back" href="{{ route('admin.dashboard') }}">← Retour au tableau de bord</a>
    </div>
    <div style="font-size:13.5px;color:var(--steel)">{{ \App\Models\ContactMessage::unread()->count() }} non lu(s) · {{ $messages->total() }} au total</div>
  </div>

  @if(session('success'))<div class="flash">{{ session('success') }}</div>@endif

  @forelse($messages as $m)
    <div class="msg {{ $m->isRead() ? '' : 'unread' }}">
      <div class="h">
        <div class="nm">{{ $m->name }}@unless($m->isRead())<span class="badge">Nouveau</span>@endunless</div>
        <div class="dt">{{ $m->created_at->format('d/m/Y à H:i') }}</div>
      </div>
      <div class="meta">
        <span>📱 <a href="https://wa.me/{{ preg_replace('/\D/','',$m->phone) }}" target="_blank" rel="noopener">{{ $m->phone }}</a></span>
        @if($m->email)<span>✉️ <a href="mailto:{{ $m->email }}">{{ $m->email }}</a></span>@endif
      </div>
      @if($m->subject)<div class="sub">{{ $m->subject }}</div>@endif
      <div class="body">{{ $m->message }}</div>
      <div class="acts">
        <form method="POST" action="{{ route('messages.read', $m) }}">@csrf
          <button class="btn btn-read" type="submit">{{ $m->isRead() ? 'Marquer non lu' : 'Marquer comme lu' }}</button>
        </form>
        <form method="POST" action="{{ route('messages.destroy', $m) }}" onsubmit="return confirm('Supprimer ce message ?')">@csrf
          <button class="btn btn-del" type="submit">Supprimer</button>
        </form>
      </div>
    </div>
  @empty
    <div class="empty"><h3>Aucun message</h3><p>Les messages envoyés depuis la page Contact du site apparaîtront ici.</p></div>
  @endforelse

  <div class="pag">{{ $messages->links() }}</div>
</div>
</body>
</html>
