@extends('public.layout')
@section('title', 'Nos offres disponibles — Lorny Conseils Management')

@section('styles')
.bgrid{display:grid;grid-template-columns:repeat(3,1fr);gap:28px}
.bcard{background:#13213c;border:1px solid var(--line);overflow:hidden;display:flex;flex-direction:column;position:relative}
.bcard .imgwrap{height:230px;position:relative;overflow:hidden;flex:0 0 auto}
.bcard .img{position:absolute;inset:0;background:url('{{ asset('image/lorny-hero.jpg') }}') center/cover,linear-gradient(135deg,#1c3a6e,#0c1730);transition:transform .8s ease}
.bcard:hover .img{transform:scale(1.06)}
.bcard .imgwrap::after{content:"";position:absolute;inset:0;background:linear-gradient(180deg,rgba(11,20,38,.1) 30%,rgba(11,20,38,.9));z-index:1}
.bcard .tag{position:absolute;top:16px;left:16px;z-index:2;font-family:var(--mono);font-size:10px;letter-spacing:.12em;text-transform:uppercase;padding:6px 11px;border:1px solid;background:rgba(11,20,38,.35)}
.bcard .tag.disponible{color:var(--orange);border-color:var(--orange)}
.bcard .tag.reserve{color:var(--orange-soft);border-color:var(--orange-soft)}
.bcard .tag.vendu{color:var(--muted);border-color:var(--muted)}
.bcard .ttl{position:absolute;left:22px;right:22px;bottom:18px;z-index:2}
.bcard .ttl .ty{font-size:12px;letter-spacing:.14em;text-transform:uppercase;color:var(--orange-soft)}
.bcard .ttl h3{font-family:var(--serif);font-weight:500;font-size:23px;line-height:1.15;color:var(--light);margin-top:5px}
.bcard .body{padding:22px;display:flex;flex-direction:column;gap:16px;flex:1}
.bcard .price{font-family:var(--serif);font-weight:700;font-size:30px;color:var(--orange)}
.bcard .price small{display:block;font-family:var(--mono);font-weight:400;font-size:10px;letter-spacing:.1em;text-transform:uppercase;color:var(--muted);margin-top:4px}
.brow{display:flex;justify-content:space-between;gap:14px;padding:14px 0;border-top:1px solid var(--line)}
.brow .k{font-family:var(--mono);font-size:11px;letter-spacing:.06em;text-transform:uppercase;color:var(--muted)}
.brow .v{font-family:var(--sans);font-weight:500;font-size:14px;color:var(--light)}
.clo{display:flex;align-items:center;gap:8px;font-size:13px;color:var(--light2)}
.clo .dot{width:8px;height:8px;border-radius:50%;flex:0 0 auto}
.clo.inc .dot{background:#46b07c}.clo.opt .dot{background:var(--orange)}
.clo .opt-amt{font-family:var(--mono);font-size:12px;color:var(--orange-soft)}
.bcard .cta{margin-top:auto}
@media(max-width:960px){.bgrid{grid-template-columns:1fr}}
@endsection

@section('content')
<section class="page-banner">
  <div class="bg"></div><div class="ov"></div>
  <div class="wrap inner">
    <div class="crumb">Accueil — Nos offres</div>
    <h1>Nos offres <em>disponibles</em>.</h1>
    <p>Des villas et duplex sélectionnés, présentés avec leur surface, leur prix, l'apport initial requis et l'option clôture.</p>
  </div>
</section>

<section class="section">
  <div class="wrap">
    <div class="bgrid">
      @forelse($biens as $b)
        <div class="bcard">
          <div class="imgwrap">
            <div class="img" @if($b->photo) style="background:url('{{ asset('storage/'.$b->photo) }}') center/cover" @endif></div>
            <span class="tag {{ $b->status }}">{{ $b->statusLabel() }}</span>
            <div class="ttl">@if($b->type)<div class="ty">{{ $b->type }}</div>@endif<h3>{{ $b->name }}</h3></div>
          </div>
          <div class="body">
            <div class="price">{{ number_format((float)$b->price,0,',',' ') }} FCFA<small>Prix du bien</small></div>
            <div>
              <div class="brow"><span class="k">Surface</span><span class="v">{{ $b->surface ? number_format($b->surface,0,',',' ').' m²' : '—' }}</span></div>
              <div class="brow"><span class="k">Apport initial ({{ $b->apport_pct }} %)</span><span class="v">{{ number_format($b->apportInitial(),0,',',' ') }} FCFA</span></div>
            </div>
            @if($b->cloture_incluse)
              <div class="clo inc"><span class="dot"></span> {{ $b->clotureLabel() }}</div>
            @else
              <div class="clo opt"><span class="dot"></span> {{ $b->clotureLabel() }} · <span class="opt-amt">clôture +{{ number_format((float)$b->cloture_prix,0,',',' ') }} F</span></div>
            @endif
            <div class="cta"><a class="btn btn-orange" href="{{ route('register.create') }}" style="width:100%;justify-content:center">Adhérer à ce bien <span>→</span></a></div>
          </div>
        </div>
      @empty
        <div class="bcard"><div class="body"><h3 style="font-family:var(--serif);color:var(--light)">Biens à venir</h3><p style="color:var(--light2)">De nouveaux biens seront publiés très prochainement.</p></div></div>
      @endforelse
    </div>
  </div>
</section>

<section class="section cta-band light" style="padding-top:96px">
  <div class="wrap">
    <div class="sec-eyebrow c"><span class="eyebrow">Une question ?</span></div>
    <h2>Un bien vous <em>intéresse</em> ?</h2>
    <p>Créez votre compte : un conseiller vous présente le bien, l'option clôture et l'échéancier adapté.</p>
    <a class="btn btn-orange" href="{{ route('register.create') }}">Créer un compte <span>→</span></a>
  </div>
</section>
@endsection
