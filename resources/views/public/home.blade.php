@extends('public.layout')
@section('title', 'Lorny Conseils Management')
@section('meta_description', "Devenez propriétaire à Abidjan avec Lorny Conseils Management : biens sélectionnés, paiement échelonné et suivi de votre dossier en temps réel. Apport dès 35 %.")

@php
  $heroImg = $biens->firstWhere('photo') ? asset('storage/'.$biens->firstWhere('photo')->photo) : asset('image/slider1.jpeg');
  // Données réelles pour le simulateur
  $sim = $biens->map(fn($b)=>[
    'id'=>$b->id,'name'=>$b->name,'type'=>$b->type,'price'=>(float)$b->price,'apport'=>(int)$b->apport_pct,
    'img'=> $b->photo ? asset('storage/'.$b->photo) : $heroImg,
  ])->values();
  $ref = $biens->first();
@endphp

@section('styles')
.hero{position:relative;height:760px;min-height:660px;display:flex;align-items:flex-end;overflow:hidden;background:var(--ink)}
#heroBg{position:absolute;inset:0;background:url('{{ $heroImg }}') center/cover no-repeat;animation:heroZoom 2s ease-out both;will-change:transform}
.hero .grad{position:absolute;inset:0;background:linear-gradient(180deg,rgba(11,22,40,.55) 0%,rgba(11,22,40,.12) 38%,rgba(11,22,40,.82) 100%)}
.hero .hc{position:relative;z-index:5;width:100%;padding-bottom:0}
.hero .kicker{display:inline-flex;align-items:center;gap:11px;color:var(--orange-soft);font-size:12px;font-weight:600;letter-spacing:.26em;text-transform:uppercase;margin-bottom:20px;animation:slideUp .9s ease .1s both}
.hero .kicker::before{content:"";width:28px;height:1px;background:var(--orange)}
.hero h1{font-family:var(--serif);font-weight:500;font-size:clamp(46px,7vw,78px);line-height:1.02;letter-spacing:-.01em;color:#fff;animation:slideUp .9s ease .2s both}
.hero h1 em{font-style:italic;color:var(--orange-soft)}
.hero .sub{color:rgba(255,255,255,.84);font-weight:300;font-size:18px;line-height:1.65;max-width:540px;margin-top:20px;animation:slideUp .9s ease .3s both}
/* barre de recherche flottante */
.searchbar{background:#fff;border-radius:5px;padding:9px;display:grid;grid-template-columns:1.5fr 1fr 1fr auto;box-shadow:0 32px 70px -28px rgba(11,22,40,.6);max-width:980px;margin-top:40px;transform:translateY(50%);animation:slideUp .9s ease .45s both}
.searchbar .f{padding:13px 22px;border-right:1px solid var(--line)}
.searchbar .f label{display:block;font-size:10.5px;letter-spacing:.14em;font-weight:600;color:var(--muted2);text-transform:uppercase;margin-bottom:5px}
.searchbar .f input,.searchbar .f select{border:none;outline:none;font-family:var(--sans);font-size:15px;color:var(--ink);width:100%;font-weight:500;background:transparent;cursor:pointer}
.searchbar button{background:var(--ink);border:none;color:#fff;font-family:var(--sans);font-weight:600;font-size:14.5px;padding:0 36px;margin:5px;border-radius:3px;cursor:pointer;transition:.25s}
.searchbar button:hover{background:var(--orange)}
.hero-spacer{height:96px}

/* bandeau confiance */
.trust .wrap{display:grid;grid-template-columns:1.3fr 1fr 1fr 1fr;gap:46px;align-items:start}
.trust .lead{font-family:var(--serif);font-weight:400;font-size:24px;line-height:1.42;color:rgba(255,255,255,.82)}
.tstat{border-left:1px solid var(--line-d);padding-left:26px}
.tstat .n{font-family:var(--serif);font-weight:600;font-size:44px;color:#fff;line-height:1}
.tstat .l{color:rgba(255,255,255,.6);font-size:13px;margin-top:8px;line-height:1.4}

/* simulateur */
.sim-grid{display:grid;grid-template-columns:1.35fr 1fr;gap:32px;align-items:start}
.sim-card{background:#fff;border:1px solid var(--line);border-radius:8px;padding:34px 36px}
.sim-step{display:flex;gap:18px;padding:26px 0;border-bottom:1px solid var(--line)}
.sim-step:first-child{padding-top:0}.sim-step:last-child{border-bottom:none;padding-bottom:0}
.sim-no{width:30px;height:30px;border-radius:50%;background:var(--ink);color:#fff;display:flex;align-items:center;justify-content:center;font-family:var(--serif);font-weight:700;font-size:16px;flex-shrink:0}
.sim-step .t{font-size:15.5px;font-weight:600;color:var(--navy)}
.sim-bien{display:flex;flex-direction:column;gap:9px;margin-top:14px}
.sim-bien button{width:100%;text-align:left;font-family:var(--sans);font-size:14px;font-weight:600;padding:13px 16px;border-radius:5px;cursor:pointer;transition:.2s;display:flex;justify-content:space-between;align-items:center;gap:10px;background:var(--paper2);color:var(--navy);border:1px solid var(--line)}
.sim-bien button.on{background:var(--ink);color:#fff;border-color:var(--ink)}
.sim-bien button .pl{font-size:12.5px;font-weight:500;opacity:.85}
.sim-apport-head{display:flex;justify-content:space-between;align-items:baseline}
.sim-apport-head .pct{font-size:13.5px;font-weight:700;color:var(--orange2)}
input[type=range]{width:100%;margin-top:16px;accent-color:var(--ink);cursor:pointer}
.sim-range-lbl{display:flex;justify-content:space-between;font-size:11px;color:var(--muted2);margin-top:4px}
.sim-months{display:grid;grid-template-columns:repeat(3,1fr);gap:9px;margin-top:14px}
.sim-months button{font-family:var(--sans);font-size:15px;font-weight:700;padding:13px 0;border-radius:5px;cursor:pointer;transition:.2s;background:#fff;color:var(--navy);border:1px solid var(--line)}
.sim-months button.on{background:var(--ink);color:#fff;border-color:var(--ink)}
.sim-hint{font-size:12px;color:var(--muted2);margin-top:11px}
.sim-recap{background:var(--ink);border-radius:8px;padding:32px 32px 28px;position:sticky;top:90px;box-shadow:0 40px 80px -36px rgba(11,22,40,.6)}
.sim-recap .rr{display:flex;justify-content:space-between;padding:11px 0;border-bottom:1px solid var(--line-d)}
.sim-recap .rr:last-of-type{border-bottom:none}
.sim-recap .rr span:first-child{color:rgba(255,255,255,.6);font-size:13.5px}
.sim-recap .rr span:last-child{color:#fff;font-size:13.5px;font-weight:600}
.sim-mens{background:rgba(237,139,28,.14);border:1px solid rgba(237,139,28,.4);border-radius:7px;padding:20px 22px;margin-top:16px}
.sim-mens .ml{color:var(--orange-soft);font-size:11px;letter-spacing:.14em;font-weight:600;text-transform:uppercase;margin-bottom:7px}
.sim-mens .mv{color:#fff;font-family:var(--serif);font-weight:600;font-size:34px;line-height:1}
.sim-mens .ms{color:rgba(255,255,255,.6);font-size:13px;margin-top:7px}
.sim-note{color:rgba(255,255,255,.45);font-size:11.5px;text-align:center;margin-top:12px;line-height:1.5}

/* financement + suivi */
.fin-grid{display:grid;grid-template-columns:1fr 1fr;gap:64px;align-items:center}
.fin-point{display:flex;gap:16px;align-items:flex-start;padding:16px 0;border-top:1px solid var(--line-d)}
.fin-point .k{color:var(--orange-soft);font-family:var(--serif);font-size:20px;font-weight:700;min-width:26px}
.fin-point .t{color:#fff;font-size:15px;font-weight:600;margin-bottom:3px}
.fin-point .b{color:rgba(255,255,255,.6);font-size:13.5px;line-height:1.55;font-weight:300}
.suivi{background:var(--paper2);border-radius:8px;padding:30px;box-shadow:0 44px 90px -34px rgba(0,0,0,.6)}
.suivi .top{display:flex;justify-content:space-between;align-items:center;margin-bottom:4px}
.suivi .top .lb{font-size:11px;letter-spacing:.14em;color:var(--muted2);font-weight:600}
.suivi .top .ok{display:flex;align-items:center;gap:7px;font-size:11.5px;color:#3f9d6b;font-weight:600}
.suivi .top .ok span{width:7px;height:7px;border-radius:50%;background:#3f9d6b}
.suivi h4{font-family:var(--serif);font-size:25px;color:var(--navy);font-weight:600}
.suivi .dossier{color:var(--muted);font-size:13px;margin-bottom:22px}
.suivi .pr-head{display:flex;justify-content:space-between;align-items:baseline;margin-bottom:9px;font-size:13px}
.suivi .pr-head .a{color:var(--muted);font-weight:500}.suivi .pr-head .b{color:var(--navy);font-weight:600}
.suivi .bar{height:9px;background:var(--line);border-radius:6px;overflow:hidden;margin-bottom:8px}
.suivi .bar i{display:block;height:100%;background:linear-gradient(90deg,var(--orange),var(--orange-soft));border-radius:6px}
.suivi .meta{display:flex;justify-content:space-between;font-size:12px;color:var(--muted2);margin-bottom:22px}
.suivi .next{background:#fff;border:1px solid var(--line);border-radius:6px;padding:15px 18px;display:flex;justify-content:space-between;align-items:center;gap:12px}
.suivi .next .nl{font-size:11px;letter-spacing:.08em;color:var(--muted2);font-weight:600;margin-bottom:4px}
.suivi .next .nv{font-size:14.5px;color:var(--navy);font-weight:600}
.suivi .next .reg{background:var(--ink);color:#fff;font-size:12px;font-weight:600;padding:9px 16px;border-radius:3px}
.suivi .sync{display:flex;align-items:center;gap:9px;color:var(--muted);font-size:12px;margin-top:14px}
.suivi .sync .d{color:var(--orange2);font-weight:700}

/* services */
.serv-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:50px}
.serv .num{font-family:var(--serif);font-size:34px;color:var(--orange2);font-weight:600;margin-bottom:16px}
.serv h3{font-size:19px;font-weight:600;color:var(--navy);margin-bottom:11px}
.serv p{color:var(--muted);font-size:14.5px;line-height:1.65}

@media(max-width:960px){
  .searchbar{grid-template-columns:1fr 1fr;transform:none;margin-top:28px;max-width:100%}
  .searchbar .f:nth-child(2){border-right:none}
  .searchbar button{grid-column:1/-1;padding:15px}
  .hero-spacer{height:0}
  .trust .wrap{grid-template-columns:1fr 1fr;gap:34px}
  .sim-grid,.fin-grid{grid-template-columns:1fr;gap:30px}
  .sim-recap{position:static}
  .serv-grid{grid-template-columns:1fr;gap:34px}
}
@media(max-width:520px){.trust .wrap{grid-template-columns:1fr}.sim-months{grid-template-columns:repeat(3,1fr)}}
@endsection

@section('content')
<section class="hero" id="hero">
  <div id="heroBg"></div>
  <div class="grad"></div>
  <div class="wrap hc">
    <div class="kicker">L'immobilier, géré avec rigueur</div>
    <h1>Trouvez le bien<br><em>qui vous ressemble.</em></h1>
    <p class="sub">Acquisition et paiement échelonné, avec le suivi de votre dossier en temps réel — où que vous soyez.</p>
    <form class="searchbar" method="GET" action="{{ route('biens') }}">
      <div class="f"><label>Recherche</label><input name="q" value="{{ request('q') }}" placeholder="Villa, duplex, programme…"></div>
      <div class="f"><label>Type de bien</label>
        <select name="type">
          <option value="">Tous les types</option>
          <option>Villa</option><option>Duplex</option><option>Appartement</option><option>Terrain</option>
        </select>
      </div>
      <div class="f"><label>Budget max</label>
        <select name="budget">
          <option value="">Indifférent</option>
          <option value="50000000">50 M FCFA</option>
          <option value="80000000">80 M FCFA</option>
          <option value="150000000">150 M FCFA</option>
          <option value="300000000">300 M FCFA +</option>
        </select>
      </div>
      <button type="submit">Rechercher</button>
    </form>
  </div>
</section>
<div class="hero-spacer"></div>

{{-- BANDEAU CONFIANCE --}}
<section class="section tight dark trust">
  <div class="wrap">
    <div data-reveal>
      <div class="eyebrow light" style="margin-bottom:14px">Bureau d'études LCM</div>
      <p class="lead">Bureau d'études de gestion immobilière à Abidjan, nous conjuguons rigueur et proximité pour valoriser votre patrimoine.</p>
    </div>
    <div class="tstat" data-reveal><div class="n" data-count="{{ max(3,$programmes->count()) }}">{{ max(3,$programmes->count()) }}</div><div class="l">Programmes accompagnés</div></div>
    <div class="tstat" data-reveal><div class="n">35 %</div><div class="l">Apport pour démarrer</div></div>
    <div class="tstat" data-reveal><div class="n">100 %</div><div class="l">Suivi de dossier en ligne</div></div>
  </div>
</section>

{{-- BIENS À LA UNE --}}
<section class="section">
  <div class="wrap">
    <div class="head-row">
      <div data-reveal>
        <div class="eyebrow">Sélection du moment</div>
        <h2 class="sec-title" style="margin-top:14px">Biens <em>disponibles</em></h2>
      </div>
      <a class="linkmore" href="{{ route('biens') }}" data-reveal>Voir toutes nos offres →</a>
    </div>
    <div class="props">
      @forelse($biens as $b)
        <a class="pcard" href="{{ route('biens') }}" data-reveal>
          <div class="img">
            <div class="bg" @if($b->photo) style="background-image:url('{{ asset('storage/'.$b->photo) }}')" @endif></div>
            <span class="tag {{ $b->status }}">{{ $b->statusLabel() }}</span>
          </div>
          <div class="pbody">
            @if($b->type)<div class="pty">{{ $b->type }}</div>@endif
            <h3>{{ $b->name }}</h3>
            <div class="specs">
              <span>{{ $b->surface ? number_format($b->surface,0,',',' ').' m²' : '—' }}</span>
              <span>Apport {{ $b->apport_pct }} %</span>
              <span>{{ $b->clotureLabel() }}</span>
            </div>
            <div class="pf">
              <div class="price">{{ number_format((float)$b->price/1000000,0,',',' ') }} M<small>FCFA</small></div>
              <div class="apo">échelonné<br>apport {{ number_format($b->apportInitial()/1000000,1,',',' ') }} M</div>
            </div>
          </div>
        </a>
      @empty
        <div class="pcard"><div class="pbody"><h3>Biens à venir</h3><p style="color:var(--muted)">De nouvelles offres seront publiées très prochainement.</p></div></div>
      @endforelse
    </div>
  </div>
</section>

{{-- SIMULATEUR --}}
@if($sim->count())
<section class="section cream" id="simulateur">
  <div class="wrap">
    <div class="sec-head" data-reveal style="max-width:620px">
      <div class="eyebrow">Simulateur d'achat</div>
      <h2 class="sec-title">Composez votre achat,<br><em>étape par étape.</em></h2>
      <p class="sec-sub">Choisissez votre bien, ajustez votre apport et la durée de paiement. Votre mensualité estimée s'actualise instantanément.</p>
    </div>
    <div class="sim-grid">
      <div class="sim-card" data-reveal>
        <div class="sim-step">
          <div class="sim-no">1</div>
          <div style="flex:1">
            <div class="t">Choisissez votre bien</div>
            <div class="sim-bien" id="simBiens"></div>
          </div>
        </div>
        <div class="sim-step">
          <div class="sim-no">2</div>
          <div style="flex:1">
            <div class="sim-apport-head"><div class="t">Apport initial</div><div class="pct"><span id="apPct">35</span> % · <span id="apVal">—</span></div></div>
            <input type="range" id="apRange" min="35" max="90" step="5" value="35">
            <div class="sim-range-lbl"><span>35 %</span><span>90 %</span></div>
          </div>
        </div>
        <div class="sim-step">
          <div class="sim-no">3</div>
          <div style="flex:1">
            <div class="t">Durée de paiement</div>
            <div class="sim-months" id="simMonths"></div>
            <div class="sim-hint">Nombre de mensualités après versement de l'apport.</div>
          </div>
        </div>
      </div>
      <div class="sim-recap" data-reveal>
        <div class="eyebrow light" style="margin-bottom:18px">Récapitulatif</div>
        <div class="rr"><span>Prix du bien</span><span id="rPrice">—</span></div>
        <div class="rr"><span>Apport (<span id="rPct">35</span> %)</span><span id="rApport">—</span></div>
        <div class="rr"><span>Reste à financer</span><span id="rReste">—</span></div>
        <div class="sim-mens">
          <div class="ml">Mensualité estimée</div>
          <div class="mv" id="rMens">—</div>
          <div class="ms" id="rEch">—</div>
        </div>
        <a class="btn btn-light" href="{{ route('register.create') }}" style="width:100%;margin-top:16px">Lancer mon dossier <span>→</span></a>
        <div class="sim-note">Estimation indicative, sans engagement. Les frais d'ouverture de dossier (500 000 FCFA) sont distincts.</div>
      </div>
    </div>
  </div>
</section>
@endif

{{-- FINANCEMENT + SUIVI --}}
<section class="section dark" id="financement">
  <div class="wrap fin-grid">
    <div data-reveal>
      <div class="eyebrow light">Acheter autrement</div>
      <h2 class="sec-title" style="margin-top:14px">Le paiement échelonné,<br><em>en toute transparence.</em></h2>
      <p class="sec-sub">Devenez propriétaire selon un plan de versements adapté à votre capacité. Chaque échéance, chaque document et l'état d'avancement de votre chantier vous suivent en temps réel — sur mobile, tablette ou ordinateur.</p>
      <div style="margin-top:26px">
        <div class="fin-point"><div class="k">01</div><div><div class="t">Une durée au choix</div><div class="b">Réglez sur 12, 24 ou 36 mois (de 1 à 3 ans), selon votre capacité.</div></div></div>
        <div class="fin-point"><div class="k">02</div><div><div class="t">Suivi en temps réel</div><div class="b">Échéances, montants réglés et documents accessibles partout depuis votre espace.</div></div></div>
        <div class="fin-point"><div class="k">03</div><div><div class="t">Avancement du chantier</div><div class="b">Suivez votre logement niveau par niveau, en photos, avec une notification à chaque étape.</div></div></div>
      </div>
    </div>
    <div class="suivi" data-reveal>
      <div class="top"><div class="lb">MON ACQUISITION</div><div class="ok"><span></span>À jour</div></div>
      <h4>{{ $ref?->name ?? 'Villa Belvédère' }}</h4>
      <div class="dossier">Abidjan · Côte d'Ivoire — Dossier #LCM-2048</div>
      @php
        $rp = $ref ? (float)$ref->price : 52000000;
        $rverse = round($rp*0.40); $rpct = 40;
      @endphp
      <div class="pr-head"><span class="a">Versé · {{ number_format($rverse,0,',',' ') }} FCFA</span><span class="b">sur {{ number_format($rp,0,',',' ') }} FCFA</span></div>
      <div class="bar"><i style="width:{{ $rpct }}%"></i></div>
      <div class="meta"><span>Mensualité 10 / 24</span><span>{{ $rpct }} % réglé</span></div>
      <div class="next">
        <div><div class="nl">PROCHAINE ÉCHÉANCE</div><div class="nv">{{ number_format(round($rp*0.05),0,',',' ') }} FCFA — 15 juillet 2026</div></div>
        <div class="reg">Régler</div>
      </div>
      <div class="sync"><span class="d">⌖</span> Synchronisé sur tous vos appareils — consultable partout, à tout moment.</div>
    </div>
  </div>
</section>

{{-- SERVICES --}}
<section class="section">
  <div class="wrap">
    <div class="sec-head c" data-reveal>
      <div class="eyebrow c">Notre accompagnement</div>
      <h2 class="sec-title">Un service à chaque étape</h2>
    </div>
    <div class="serv-grid">
      <div class="serv" data-reveal><div class="num">01</div><h3>Conseil &amp; sélection</h3><p>Des biens et programmes vérifiés avant publication, présentés avec leur prix, leur surface et l'apport requis — sans frais caché.</p></div>
      <div class="serv" data-reveal><div class="num">02</div><h3>Paiement échelonné</h3><p>Devenez propriétaire grâce à un échéancier personnalisé, mensuel ou trimestriel, étalé selon votre capacité après l'apport de 35 %.</p></div>
      <div class="serv" data-reveal><div class="num">03</div><h3>Suivi en temps réel</h3><p>Suivez l'état de votre dossier, vos versements et l'avancement des travaux à tout moment, depuis votre espace membre ou l'application.</p></div>
    </div>
  </div>
</section>

{{-- CTA --}}
<section class="cta-img">
  <div class="bg" style="background-image:url('{{ $heroImg }}')"></div>
  <div class="ov"></div>
  <div class="inner" data-reveal>
    <h2>Créez votre <em>espace personnel</em></h2>
    <p>Enregistrez votre projet, suivez vos échéances et échangez avec un conseiller dédié. Votre adhésion commence ici.</p>
    <a class="btn btn-orange" href="{{ route('register.create') }}">Créer un compte gratuitement <span>→</span></a>
  </div>
</section>
@endsection

@push('scripts')
<script>
(function(){
  const BIENS = @json($sim);
  if(!BIENS.length) return;
  const monthsOpts=[12,24,36];
  let iBien=0, apport=Math.max(35, BIENS[0].apport||35), months=24;
  const fmt=n=>Math.round(n).toLocaleString('fr-FR')+' FCFA';
  const fmtM=n=>{const m=n/1000000; return (m>=10?Math.round(m):m.toFixed(1)).toString().replace('.',',')+' M FCFA';};
  const $=id=>document.getElementById(id);

  function renderBiens(){
    $('simBiens').innerHTML=BIENS.map((b,i)=>
      `<button type="button" class="${i===iBien?'on':''}" data-i="${i}"><span>${b.name}</span><span class="pl">${fmtM(b.price)}</span></button>`
    ).join('');
    $('simBiens').querySelectorAll('button').forEach(btn=>btn.onclick=()=>{iBien=+btn.dataset.i; const min=Math.max(35,BIENS[iBien].apport||35); $('apRange').min=min; if(apport<min){apport=min;$('apRange').value=min;} update();});
  }
  function renderMonths(){
    $('simMonths').innerHTML=monthsOpts.map(m=>`<button type="button" class="${m===months?'on':''}" data-m="${m}">${m}</button>`).join('');
    $('simMonths').querySelectorAll('button').forEach(btn=>btn.onclick=()=>{months=+btn.dataset.m; update();});
  }
  function update(){
    const b=BIENS[iBien], price=b.price, ap=Math.round(price*apport/100), reste=price-ap;
    $('apPct').textContent=apport; $('rPct').textContent=apport; $('apVal').textContent=fmt(ap);
    $('rPrice').textContent=fmt(price); $('rApport').textContent='− '+fmt(ap); $('rReste').textContent=fmt(reste);
    $('rMens').textContent=fmt(reste/months); $('rEch').textContent='sur '+months+' mensualités';
    renderBiens(); renderMonths();
  }
  $('apRange').addEventListener('input',e=>{apport=+e.target.value; update();});
  $('apRange').value=apport;
  update();
})();
</script>
@endpush
