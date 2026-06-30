@extends('public.layout')
@section('title', "Le Bureau d'études LCM — Lorny Conseils Management")
@section('meta_description', "Lorny Conseils Management, bureau d'études de gestion immobilière à Abidjan : conseil, adhésion, paiement échelonné et suivi de chantier. Méthode, transparence, proximité.")

@php
  $hero = optional($biens->firstWhere('photo'))->photo ? asset('storage/'.$biens->firstWhere('photo')->photo) : asset('image/slider1.jpeg');
@endphp

@section('styles')
.intro-grid{display:grid;grid-template-columns:1.05fr .95fr;gap:64px;align-items:center}
.intro-grid .lead{color:var(--text);font-size:17px;line-height:1.9;margin-top:18px}
.intro-grid .lead strong{color:var(--navy);font-weight:600}
.sign{font-family:var(--serif);font-style:italic;font-size:20px;color:var(--orange2);margin-top:26px}
.frame{position:relative}
.frame .ph{height:500px;border-radius:8px;overflow:hidden;background:url('{{ $hero }}') center/cover;position:relative}
.frame .ph::after{content:"";position:absolute;inset:0;background:linear-gradient(180deg,rgba(11,22,40,.08),rgba(11,22,40,.42))}
.frame .badge{position:absolute;left:-22px;bottom:32px;z-index:2;background:#fff;border-radius:12px;padding:20px 24px;box-shadow:0 24px 50px -18px rgba(11,22,40,.5)}
.frame .badge .n{font-family:var(--serif);font-weight:700;font-size:34px;color:var(--orange2);line-height:1}
.frame .badge .l{font-size:11px;letter-spacing:.08em;text-transform:uppercase;color:var(--muted);margin-top:6px}
.values{display:grid;grid-template-columns:repeat(4,1fr);gap:24px}
.valc{padding:32px 28px;background:#fff;border:1px solid var(--line);border-radius:8px;transition:transform .3s,box-shadow .3s}
.valc:hover{transform:translateY(-6px);box-shadow:0 24px 46px -24px rgba(16,36,63,.3)}
.valc .ic{font-size:26px;margin-bottom:16px}
.valc h4{font-family:var(--serif);font-weight:600;font-size:20px;color:var(--navy);margin-bottom:9px}
.valc p{color:var(--muted);font-size:14px;line-height:1.7}
.method{display:grid;grid-template-columns:.95fr 1.05fr;gap:64px;align-items:center}
.method .ph{height:480px;border-radius:8px;background:url('{{ $hero }}') center/cover;position:relative;overflow:hidden}
.method .ph::after{content:"";position:absolute;inset:0;background:linear-gradient(180deg,rgba(11,22,40,.12),rgba(11,22,40,.5))}
.checklist{list-style:none;margin-top:28px;display:grid;gap:16px}
.checklist li{display:flex;gap:15px;align-items:flex-start}
.checklist .ck{flex:0 0 auto;width:28px;height:28px;border-radius:50%;background:linear-gradient(135deg,var(--orange),var(--orange2));display:flex;align-items:center;justify-content:center;color:#fff;font-size:14px;font-weight:700}
.checklist .tx{font-size:15.5px;color:var(--text);line-height:1.6}
.checklist .tx b{color:var(--navy);font-weight:600}
.quote-band{text-align:center;background:var(--paper2);border-top:1px solid var(--line);border-bottom:1px solid var(--line)}
.quote-band .q{font-family:var(--serif);font-weight:500;font-size:clamp(26px,3.4vw,40px);line-height:1.34;color:var(--navy);max-width:24ch;margin:0 auto}
.quote-band .q em{font-style:italic;color:var(--orange2)}
.quote-band .who{margin-top:24px;font-size:12px;letter-spacing:.14em;text-transform:uppercase;color:var(--orange2);font-weight:600}
@media(max-width:960px){.intro-grid,.method{grid-template-columns:1fr;gap:34px}.values{grid-template-columns:1fr 1fr}.frame .ph,.method .ph{height:340px}.frame .badge{left:18px}}
@media(max-width:520px){.values{grid-template-columns:1fr}}
@endsection

@section('content')
<section class="page-head">
  <div class="wrap">
    <div class="crumb">Accueil — Le Bureau d'études LCM</div>
    <h1>Le Bureau d'études <em>LCM</em></h1>
    <p>Gestion immobilière, accompagnement humain et méthode rigoureuse — pour faire de l'accès à la propriété un parcours clair et serein.</p>
  </div>
</section>

<section class="section">
  <div class="wrap intro-grid">
    <div data-reveal>
      <div class="eyebrow">Qui sommes-nous</div>
      <h2 class="sec-title" style="margin-top:14px">Bâtisseurs de<br><em>patrimoine</em>, à Abidjan.</h2>
      <p class="lead">Lorny Conseils Management accompagne particuliers et investisseurs dans l'accès à la propriété. Nous <strong>sélectionnons des programmes fonciers et résidentiels</strong>, structurons des échéanciers clairs et suivons chaque chantier jusqu'à la remise du titre.</p>
      <p class="lead">Notre conviction est simple : devenir propriétaire ne devrait jamais être un parcours opaque. <strong>Méthode, transparence et proximité</strong> guident chacun de nos engagements.</p>
      <div class="sign">— L'équipe Lorny Conseils Management</div>
    </div>
    <div class="frame" data-reveal>
      <div class="ph"></div>
      <div class="badge"><div class="n">Abidjan</div><div class="l">Côte d'Ivoire</div></div>
    </div>
  </div>
</section>

<section class="section cream">
  <div class="wrap">
    <div class="sec-head" data-reveal>
      <div class="eyebrow">Nos engagements</div>
      <h2 class="sec-title">Quatre principes,<br>une même <em>exigence.</em></h2>
    </div>
    <div class="values">
      <div class="valc" data-reveal><div class="ic">🔍</div><h4>Transparence</h4><p>Des prix clairs, des échéanciers écrits, aucun frais caché. Vous savez toujours où vous en êtes.</p></div>
      <div class="valc" data-reveal><div class="ic">📐</div><h4>Rigueur</h4><p>Chaque dossier est vérifié, chaque versement tracé, chaque étape documentée.</p></div>
      <div class="valc" data-reveal><div class="ic">🤝</div><h4>Proximité</h4><p>Un conseiller dédié vous accompagne, de la première question à la remise du titre.</p></div>
      <div class="valc" data-reveal><div class="ic">📲</div><h4>Innovation</h4><p>Espace membre et application mobile : suivez paiements et travaux en temps réel.</p></div>
    </div>
  </div>
</section>

<section class="section">
  <div class="wrap method">
    <div class="ph" data-reveal></div>
    <div data-reveal>
      <div class="eyebrow">Notre méthode</div>
      <h2 class="sec-title" style="margin-top:14px">Pourquoi adhérer<br>avec <em>Lorny.</em></h2>
      <ul class="checklist">
        <li><span class="ck">✓</span><span class="tx"><b>Sélection exigeante</b> des programmes et des biens, vérifiés avant publication.</span></li>
        <li><span class="ck">✓</span><span class="tx"><b>Apport de 35 %</b> qui sécurise votre engagement et le nôtre.</span></li>
        <li><span class="ck">✓</span><span class="tx"><b>Échéancier sur mesure</b> : mensuel ou trimestriel, selon votre rythme.</span></li>
        <li><span class="ck">✓</span><span class="tx"><b>Suivi de chantier</b> par niveau, avec photos et notifications à chaque étape.</span></li>
        <li><span class="ck">✓</span><span class="tx"><b>Documents officiels</b> — factures, attestations et reçus — disponibles à tout moment.</span></li>
        <li><span class="ck">✓</span><span class="tx"><b>Remise du titre</b> de propriété au terme de votre parcours.</span></li>
      </ul>
    </div>
  </div>
</section>

<section class="section tight quote-band">
  <div class="wrap" data-reveal>
    <div class="eyebrow c" style="justify-content:center;margin-bottom:20px">Notre promesse</div>
    <p class="q">« Vous accompagner avec la même <em>rigueur</em> que si nous bâtissions notre propre maison. »</p>
    <div class="who">Lorny Conseils Management — Abidjan</div>
  </div>
</section>

<section class="cta-img">
  <div class="bg" style="background-image:url('{{ $hero }}')"></div>
  <div class="ov"></div>
  <div class="inner" data-reveal>
    <h2>Prêt à construire <em>avec nous</em> ?</h2>
    <p>Créez votre compte ou contactez un conseiller : nous étudions votre projet en toute confidentialité.</p>
    <div style="display:flex;gap:14px;justify-content:center;flex-wrap:wrap">
      <a class="btn btn-orange" href="{{ route('register.create') }}">Créer un compte <span>→</span></a>
      <a class="btn btn-outline light" href="{{ route('contact') }}">Nous contacter</a>
    </div>
  </div>
</section>
@endsection
