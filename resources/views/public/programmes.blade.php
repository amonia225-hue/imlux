@extends('public.layout')
@section('title', 'Programmes — Lorny Conseils Management')

@section('content')
<section class="page-banner">
  <div class="bg"></div><div class="ov"></div>
  <div class="wrap inner">
    <div class="crumb">Accueil — Programmes</div>
    <h1>Nos <em>programmes</em>.</h1>
    <p>Des biens fonciers et résidentiels sélectionnés et gérés par le bureau d'études, présentés avec leur surface utile, leur surface totale et l'état de leurs lots.</p>
  </div>
</section>

<section class="section">
  <div class="wrap">
    <div class="props">
      @forelse($programmes as $p)
        <div class="pcard">
          <div class="img"></div>
          <span class="tag">{{ $p->status === 'actif' ? 'Adhésions ouvertes' : ucfirst($p->status) }}</span>
          <div class="nm"><div class="loc">{{ $p->location }}</div><h3>{{ $p->name }}</h3></div>
          <div class="body">
            <div class="specs">
              <div><div class="k">Surface utile</div><div class="v">{{ $p->surface_utile ? number_format($p->surface_utile,0,',',' ').' m²' : '—' }}</div></div>
              <div><div class="k">Surface totale</div><div class="v">{{ $p->surface_totale ? number_format($p->surface_totale,0,',',' ').' m²' : '—' }}</div></div>
              <div><div class="k">Lots libres</div><div class="v">{{ $p->lots_disponibles_count }} / {{ $p->lots_count }}</div></div>
            </div>
            <div class="pf"><span class="apport">Apport requis · 35 %</span><a class="adh" href="{{ route('register.create') }}">Adhérer</a></div>
          </div>
        </div>
      @empty
        <div class="pcard"><div class="body"><h3 style="font-family:var(--serif);color:var(--light)">Programmes à venir</h3><div class="loc" style="color:var(--light2);margin-top:8px">Nos prochains programmes seront bientôt publiés.</div></div></div>
      @endforelse
    </div>
  </div>
</section>

<section class="section cta-band light" style="padding-top:96px">
  <div class="wrap">
    <div class="sec-eyebrow c"><span class="eyebrow">Une question ?</span></div>
    <h2>Un programme vous <em>intéresse</em> ?</h2>
    <p>Créez votre compte et un conseiller vous présente les lots disponibles et l'échéancier adapté.</p>
    <a class="btn btn-orange" href="{{ route('register.create') }}">Créer un compte <span>→</span></a>
  </div>
</section>
@endsection
