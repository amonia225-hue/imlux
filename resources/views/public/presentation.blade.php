@extends('public.layout')
@section('title', 'Le bureau d'études — Lorny Conseils Management')

@php
  $hero = optional($biens->firstWhere('photo'))->photo
        ? asset('storage/'.$biens->firstWhere('photo')->photo)
        : asset('image/banner-arch.svg');
@endphp

@section('styles')
/* intro éditorial */
.ab-intro .wrap{display:grid;grid-template-columns:1.05fr .95fr;gap:72px;align-items:center}
.ab-lead{color:var(--light2);font-weight:300;font-size:17.5px;line-height:1.9;margin-top:22px}
.ab-lead strong{color:var(--light);font-weight:500}
.ab-sign{font-family:var(--serif);font-style:italic;font-size:20px;color:var(--orange);margin-top:30px}
.ab-frame{position:relative}
.ab-frame .ph{height:520px;border-radius:22px;overflow:hidden;border:1px solid var(--line);background:url('{{ $hero }}') center/cover,linear-gradient(135deg,#1c3a6e,#0c1730);position:relative}
.ab-frame .ph::after{content:"";position:absolute;inset:0;background:linear-gradient(180deg,rgba(11,20,38,.12),rgba(11,20,38,.5))}
.ab-frame .badge{position:absolute;left:-22px;bottom:34px;z-index:2;background:#fff;color:#0B1426;border-radius:16px;padding:20px 24px;box-shadow:0 24px 50px rgba(7,12,24,.45)}
.ab-frame .badge .n{font-family:var(--serif);font-weight:700;font-size:34px;color:var(--orange2);line-height:1}
.ab-frame .badge .l{font-family:var(--mono);font-size:11px;letter-spacing:.08em;text-transform:uppercase;color:#5b647a;margin-top:6px}
.ab-tag{position:absolute;right:24px;top:24px;z-index:2;font-family:var(--mono);font-size:11px;letter-spacing:.14em;text-transform:uppercase;color:#fff;background:rgba(11,20,38,.5);border:1px solid rgba(255,255,255,.25);padding:8px 14px;border-radius:30px;backdrop-filter:blur(4px)}

/* engagements / valeurs */
.values{display:grid;grid-template-columns:repeat(4,1fr);gap:24px;margin-top:60px}
.value{padding:38px 30px;border:1px solid var(--line);border-radius:20px;background:rgba(255,255,255,.02);transition:transform .3s,box-shadow .3s,border-color .3s}
.value:hover{transform:translateY(-6px);border-color:var(--orange);box-shadow:0 22px 44px rgba(0,0,0,.3)}
.value .ic{display:inline-flex;align-items:center;justify-content:center;width:60px;height:60px;border-radius:18px;font-size:27px;margin-bottom:22px;box-shadow:0 12px 26px rgba(0,0,0,.28)}
.value:nth-child(1) .ic{background:linear-gradient(135deg,var(--orange),var(--orange2))}
.value:nth-child(2) .ic{background:linear-gradient(135deg,var(--blue2),var(--blue))}
.value:nth-child(3) .ic{background:linear-gradient(135deg,#26A66B,#177A4A)}
.value:nth-child(4) .ic{background:linear-gradient(135deg,#3566D6,#1E3A8C)}
.value h4{font-family:var(--serif);font-weight:600;font-size:21px;color:var(--light);margin-bottom:10px}
.value p{font-size:14.5px;color:var(--light2);font-weight:300;line-height:1.75}

/* méthode : split + checklist */
.ab-why .wrap{display:grid;grid-template-columns:.95fr 1.05fr;gap:72px;align-items:center}
.ab-why .ph{height:500px;border-radius:22px;overflow:hidden;border:1px solid var(--line);background:url('{{ $hero }}') center/cover,linear-gradient(135deg,#1c3a6e,#0c1730);position:relative}
.ab-why .ph::after{content:"";position:absolute;inset:0;background:linear-gradient(180deg,rgba(11,20,38,.15),rgba(11,20,38,.55))}
.checklist{list-style:none;margin-top:34px;display:grid;gap:18px}
.checklist li{display:flex;gap:16px;align-items:flex-start}
.checklist .ck{flex:0 0 auto;width:30px;height:30px;border-radius:50%;background:linear-gradient(135deg,var(--orange),var(--orange2));display:flex;align-items:center;justify-content:center;color:#fff;font-size:15px;font-weight:700;box-shadow:0 8px 18px rgba(237,139,28,.4)}
.checklist .tx{font-size:16px;color:var(--light2);font-weight:400;line-height:1.65;padding-top:3px}
.checklist .tx b{color:var(--light);font-weight:600}
/* checklist sur fond clair — lisibilité */
section.light .checklist .tx{color:#2c3850}
section.light .checklist .tx b{color:#0B1426}

/* mini biens à la une */
.ab-feat{display:grid;grid-template-columns:1fr 1fr;gap:28px;margin-top:56px}
.fbien{position:relative;border-radius:20px;overflow:hidden;border:1px solid var(--line);min-height:300px;display:flex;align-items:flex-end}
.fbien .bgi{position:absolute;inset:0;background:url('{{ $hero }}') center/cover;transition:transform .8s ease}
.fbien:hover .bgi{transform:scale(1.06)}
.fbien::after{content:"";position:absolute;inset:0;background:linear-gradient(180deg,rgba(11,20,38,.05) 30%,rgba(7,12,24,.92))}
.fbien .ct{position:relative;z-index:2;padding:30px}
.fbien .ty{font-family:var(--mono);font-size:11px;letter-spacing:.12em;text-transform:uppercase;color:var(--orange-soft)}
.fbien h3{font-family:var(--serif);font-weight:600;font-size:26px;color:#fff;margin:7px 0 6px}
.fbien .pr{font-family:var(--mono);font-size:14px;color:var(--light)}
.fbien .go{display:inline-flex;align-items:center;gap:9px;margin-top:14px;font-size:12px;letter-spacing:.12em;text-transform:uppercase;color:#fff;border-bottom:1px solid var(--orange);padding-bottom:3px}

@media(max-width:960px){.ab-intro .wrap,.ab-why .wrap{grid-template-columns:1fr;gap:42px}.values{grid-template-columns:1fr 1fr}.ab-feat{grid-template-columns:1fr}.ab-frame .ph,.ab-why .ph{height:360px}.ab-frame .badge{left:18px}}
@media(max-width:560px){.values{grid-template-columns:1fr}}
@endsection

@section('content')
<section class="page-banner">
  <div class="bg"></div><div class="ov"></div>
  <div class="wrap inner">
    <div class="crumb">Accueil — Le bureau d'études</div>
    <h1>Le bureau d'études <em>Lorny</em>.</h1>
    <p>Gestion immobilière, accompagnement humain et méthode rigoureuse — pour faire de l'accès à la propriété un parcours clair et serein.</p>
  </div>
</section>

{{-- RAISON D'ÊTRE --}}
<section class="section ab-intro">
  <div class="wrap">
    <div>
      <div class="sec-eyebrow"><span class="eyebrow">Qui sommes-nous</span></div>
      <h2 class="sec-title">Bâtisseurs de<br><em>patrimoine</em>, à Abidjan.</h2>
      <p class="ab-lead">Lorny Conseils Management accompagne particuliers et investisseurs dans l'accès à la propriété. Nous <strong>sélectionnons des programmes fonciers et résidentiels</strong>, structurons des échéanciers clairs et suivons chaque chantier jusqu'à la remise du titre.</p>
      <p class="ab-lead">Notre conviction est simple : devenir propriétaire ne devrait jamais être un parcours opaque. <strong>Méthode, transparence et proximité</strong> guident chacun de nos engagements.</p>
      <div class="ab-sign">— L'équipe Lorny Conseils Management</div>
    </div>
    <div class="ab-frame">
      <div class="ph"><span class="ab-tag">Abidjan · Côte d'Ivoire</span></div>
      <div class="badge"><div class="n">2015</div><div class="l">Au service<br>de vos projets</div></div>
    </div>
  </div>
</section>

{{-- CHIFFRES --}}
<section class="intro light">
  <div class="wrap">
    <div>
      <div class="sec-eyebrow"><span class="eyebrow">En chiffres</span></div>
      <h2 class="sec-title">Une gestion qui<br>tient ses <em>promesses</em>.</h2>
      <p class="lead">Des engagements mesurables, suivis dossier par dossier, du premier versement à la remise du titre.</p>
    </div>
    <div class="stats">
      <div class="stat"><div class="n">{{ max(3,$programmesCount) }}</div><div class="l">Programmes actifs</div></div>
      <div class="stat"><div class="n">320</div><div class="l">Adhérents accompagnés</div></div>
      <div class="stat"><div class="n">186000</div><div class="l">m² sous gestion</div></div>
      <div class="stat"><div class="n">97</div><div class="l">% de recouvrement</div></div>
    </div>
  </div>
</section>

{{-- ENGAGEMENTS --}}
<section class="section">
  <div class="wrap">
    <div class="sec-eyebrow"><span class="eyebrow">Nos engagements</span></div>
    <h2 class="sec-title">Quatre principes,<br>une même <em>exigence</em>.</h2>
    <div class="values">
      <div class="value"><div class="ic">🔍</div><h4>Transparence</h4><p>Des prix clairs, des échéanciers écrits, aucun frais caché. Vous savez toujours où vous en êtes.</p></div>
      <div class="value"><div class="ic">📐</div><h4>Rigueur</h4><p>Chaque dossier est vérifié, chaque versement tracé, chaque étape documentée.</p></div>
      <div class="value"><div class="ic">🤝</div><h4>Proximité</h4><p>Un conseiller dédié vous accompagne, de la première question à la remise du titre.</p></div>
      <div class="value"><div class="ic">📲</div><h4>Innovation</h4><p>Espace membre et application mobile : suivez vos paiements et vos travaux en temps réel.</p></div>
    </div>
  </div>
</section>

{{-- POURQUOI / MÉTHODE --}}
<section class="section ab-why light">
  <div class="wrap">
    <div class="ph"></div>
    <div>
      <div class="sec-eyebrow"><span class="eyebrow">Notre méthode</span></div>
      <h2 class="sec-title">Pourquoi adhérer<br>avec <em>Lorny</em>.</h2>
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

{{-- BIENS À LA UNE --}}
<section class="section">
  <div class="wrap">
    <div class="head-row">
      <div>
        <div class="sec-eyebrow"><span class="eyebrow">À la une</span></div>
        <h2 class="sec-title">Nos offres <em>disponibles</em>.</h2>
      </div>
      <a class="btn btn-blue" href="{{ route('biens') }}">Toutes nos offres</a>
    </div>
    <div class="ab-feat">
      @forelse($biens as $b)
        <a class="fbien" href="{{ route('biens') }}">
          <div class="bgi" @if($b->photo) style="background:url('{{ asset('storage/'.$b->photo) }}') center/cover" @endif></div>
          <div class="ct">
            @if($b->type)<div class="ty">{{ $b->type }}</div>@endif
            <h3>{{ $b->name }}</h3>
            <div class="pr">{{ number_format((float)$b->price,0,',',' ') }} FCFA</div>
            <span class="go">Voir le bien →</span>
          </div>
        </a>
      @empty
        <div class="fbien"><div class="ct"><h3>Biens à venir</h3></div></div>
      @endforelse
    </div>
  </div>
</section>

{{-- CITATION --}}
<section class="quote">
  <div class="section wrap">
    <span class="eyebrow">Notre promesse</span>
    <p class="q">« Vous accompagner avec la même <em>rigueur</em> que si nous bâtissions notre propre maison. »</p>
    <div class="who">Lorny Conseils Management — Abidjan</div>
  </div>
</section>

{{-- CTA --}}
<section class="section cta-band light" style="padding-top:96px">
  <div class="wrap">
    <div class="sec-eyebrow c"><span class="eyebrow">Faisons connaissance</span></div>
    <h2>Prêt à construire<br>avec <em>nous</em> ?</h2>
    <p>Créez votre compte ou contactez un conseiller : nous étudions votre projet en toute confidentialité.</p>
    <div style="display:flex;gap:16px;justify-content:center;flex-wrap:wrap">
      <a class="btn btn-orange" href="{{ route('register.create') }}">Créer un compte <span>→</span></a>
      <a class="btn btn-blue" href="{{ route('contact') }}">Nous contacter</a>
    </div>
  </div>
</section>
@endsection
