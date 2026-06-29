@extends('public.layout')
@section('title', 'Lorny Conseils Management')

@section('styles')
.hero{position:relative;height:100vh;min-height:720px;overflow:hidden;display:flex;align-items:center;background:#0a1120}
.hero-bg{position:absolute;inset:0;background:url('{{ asset('image/slider1.jpeg') }}') center/cover no-repeat,linear-gradient(120deg,#16315f,#0a1120);will-change:transform}
.hero-grad{position:absolute;inset:0;background:linear-gradient(90deg,rgba(7,12,24,.88) 0%,rgba(7,12,24,.55) 44%,rgba(7,12,24,.25) 72%,rgba(7,12,24,.5) 100%)}
.hero-content{position:relative;z-index:3;width:100%}
.hero h1{font-family:var(--serif);font-weight:700;font-size:clamp(54px,8vw,116px);line-height:.99;letter-spacing:-.01em;color:var(--light);margin:26px 0 0}
.hero h1 .it{display:block;font-style:italic;color:var(--orange);margin-top:6px}
.hero .sub{font-weight:300;font-size:clamp(16px,1.5vw,19px);color:var(--light2);max-width:48ch;margin:30px 0 38px;line-height:1.75}
.hero .cta{display:flex;gap:18px;flex-wrap:wrap}
.scrollv{position:absolute;right:40px;bottom:40px;z-index:3;writing-mode:vertical-rl;font-family:var(--sans);font-size:11px;letter-spacing:.32em;text-transform:uppercase;color:var(--light2);display:flex;align-items:center;gap:14px}
.scrollv::after{content:"";width:1px;height:64px;background:linear-gradient(var(--orange),transparent)}
@media(max-width:960px){.hero h1{font-size:40px}.scrollv{display:none}}
@endsection

@section('content')
<section class="hero" id="hero">
  <div class="hero-bg" id="heroBg"></div>
  <div class="hero-grad"></div>
  <div class="wrap hero-content">
    <span class="eyebrow">Bureau d'études de gestion immobilière — Abidjan</span>
    <h1>Là où vos projets<span class="it">prennent vie</span></h1>
    <p class="sub">Lorny Conseils Management accompagne votre adhésion à des programmes fonciers et résidentiels — de l'apport initial au titre de propriété.</p>
    <div class="cta">
      <a class="btn btn-orange" href="{{ route('register.create') }}">Créer un compte <span>→</span></a>
      <a class="btn btn-blue" href="{{ route('biens') }}">Voir nos offres</a>
    </div>
  </div>
  <div class="scrollv">Défiler</div>
</section>

<section class="intro light">
  <div class="wrap">
    <div>
      <div class="sec-eyebrow"><span class="eyebrow">Le bureau d'études</span></div>
      <h2 class="sec-title">L'immobilier,<br>géré avec <em>rigueur</em>.</h2>
      <p class="lead">Lorny Conseils Management accompagne une clientèle exigeante dans l'accès à la propriété : sélection des programmes, échéanciers sur mesure, suivi du chantier et remise du titre. Méthode, transparence et proximité guident chaque adhésion.</p>
    </div>
    <div class="stats">
      <div class="stat"><div class="n">{{ max(1,$programmes->count()) }}</div><div class="l">Programmes actifs</div></div>
      <div class="stat"><div class="n">320</div><div class="l">Adhérents accompagnés</div></div>
      <div class="stat"><div class="n">186000</div><div class="l">m² sous gestion</div></div>
      <div class="stat"><div class="n">97</div><div class="l">% de recouvrement</div></div>
    </div>
  </div>
</section>

<section class="section">
  <div class="wrap">
    <div class="head-row">
      <div>
        <div class="sec-eyebrow"><span class="eyebrow">À la une</span></div>
        <h2 class="sec-title">Nos offres <em>disponibles</em>.</h2>
      </div>
      <a class="btn btn-blue" href="{{ route('biens') }}">Toutes nos offres</a>
    </div>
    <div class="props">
      @forelse($biens as $b)
        <div class="pcard">
          <div class="img" @if($b->photo) style="background:url('{{ asset('storage/'.$b->photo) }}') center/cover" @endif></div>
          <span class="tag">{{ $b->statusLabel() }}</span>
          <div class="nm">@if($b->type)<div class="loc">{{ $b->type }}</div>@endif<h3>{{ $b->name }}</h3></div>
          <div class="body">
            <div class="specs">
              <div><div class="k">Surface</div><div class="v">{{ $b->surface ? number_format($b->surface,0,',',' ').' m²' : '—' }}</div></div>
              <div><div class="k">Prix</div><div class="v">{{ number_format((float)$b->price/1000000,0) }} M</div></div>
              <div><div class="k">Apport {{ $b->apport_pct }} %</div><div class="v">{{ number_format($b->apportInitial()/1000000,1) }} M</div></div>
            </div>
            <div class="pf"><span class="apport">{{ $b->clotureLabel() }}</span><a class="adh" href="{{ route('biens') }}">Détails</a></div>
          </div>
        </div>
      @empty
        <div class="pcard"><div class="body"><h3 style="font-family:var(--serif);color:var(--light)">Biens à venir</h3></div></div>
      @endforelse
    </div>
  </div>
</section>

<section class="section light">
  <div class="wrap">
    <div class="sec-eyebrow"><span class="eyebrow">L'adhésion en 3 étapes</span></div>
    <h2 class="sec-title">Un parcours <em>sur mesure</em>.</h2>
    <div class="svc-grid">
      <div class="svc"><div class="no">01</div><h4>Créez votre compte</h4><p>Inscription en ligne en quelques minutes, pièces à l'appui.</p></div>
      <div class="svc"><div class="no">02</div><h4>Validation par le bureau d'études</h4><p>Un conseiller vérifie votre dossier et active votre espace membre.</p></div>
      <div class="svc"><div class="no">03</div><h4>Choisissez &amp; adhérez</h4><p>Apport de 35 %, puis échéancier mensuel ou trimestriel.</p></div>
    </div>
    <div style="margin-top:40px"><a class="btn btn-blue" href="{{ route('adhesion') }}">En savoir plus sur l'adhésion</a></div>
  </div>
</section>

<section class="quote">
  <div class="section wrap">
    <span class="eyebrow">Ils nous ont fait confiance</span>
    <p class="q">« Un <em>accompagnement</em> clair et un suivi rigoureux. Mon échéancier était limpide du premier jour. »</p>
    <div class="who">Y. Kouassi — Adhérent, Cocody</div>
  </div>
</section>

<section class="section cta-band light">
  <div class="wrap">
    <div class="sec-eyebrow c"><span class="eyebrow">Votre projet</span></div>
    <h2>Prêt à bâtir<br>votre <em>patrimoine</em> ?</h2>
    <p>Créez votre compte aujourd'hui. Un conseiller Lorny vous accompagne dès la validation.</p>
    <a class="btn btn-orange" href="{{ route('register.create') }}">Créer un compte <span>→</span></a>
  </div>
</section>
@endsection
