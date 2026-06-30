@extends('public.layout')
@section('title', 'Nos offres — Lorny Conseils Management')
@section('meta_description', "Découvrez les biens disponibles chez Lorny Conseils Management à Abidjan : villas et duplex, prix, surface, apport requis et option clôture. Paiement échelonné.")

@section('styles')
.filters{display:flex;gap:10px;flex-wrap:wrap;align-items:center;margin-bottom:40px}
.filters form{display:flex;gap:10px;flex-wrap:wrap;background:#fff;border:1px solid var(--line);border-radius:5px;padding:8px}
.filters .f{display:flex;flex-direction:column;padding:6px 16px;border-right:1px solid var(--line)}
.filters .f:last-of-type{border-right:none}
.filters .f label{font-size:10px;letter-spacing:.12em;text-transform:uppercase;color:var(--muted2);font-weight:600;margin-bottom:3px}
.filters .f input,.filters .f select{border:none;outline:none;font:inherit;font-size:14.5px;font-weight:500;color:var(--ink);background:transparent;min-width:130px}
.filters button{background:var(--ink);color:#fff;border:none;border-radius:3px;padding:0 22px;font:inherit;font-weight:600;font-size:14px;cursor:pointer;transition:.2s}
.filters button:hover{background:var(--orange)}
.filters .reset{align-self:center;font-size:13px;color:var(--muted);border-bottom:1px solid var(--line)}
.filters .reset:hover{color:var(--orange2)}
.count-line{font-size:14px;color:var(--muted);margin-bottom:26px}
.count-line b{color:var(--navy);font-weight:600}
.empty{grid-column:1/-1;text-align:center;padding:70px 20px;background:#fff;border-radius:6px;border:1px solid var(--line)}
.empty h3{font-family:var(--serif);font-size:26px;color:var(--navy)}
.empty p{color:var(--muted);margin-top:8px}
@endsection

@section('content')
<section class="page-head">
  <div class="wrap">
    <div class="crumb">Accueil — Nos offres</div>
    <h1>Biens <em>disponibles</em></h1>
    <p>Des villas et duplex sélectionnés, présentés avec leur surface, leur prix, l'apport initial requis et l'option clôture.</p>
  </div>
</section>

<section class="section">
  <div class="wrap">
    <div class="filters">
      <form method="GET" action="{{ route('biens') }}">
        <div class="f"><label>Recherche</label><input name="q" value="{{ request('q') }}" placeholder="Nom, type…"></div>
        <div class="f"><label>Type</label>
          <select name="type">
            <option value="">Tous</option>
            @foreach(['Villa','Duplex','Appartement','Terrain'] as $t)
              <option value="{{ $t }}" @selected(request('type')===$t)>{{ $t }}</option>
            @endforeach
          </select>
        </div>
        <div class="f"><label>Budget max</label>
          <select name="budget">
            <option value="">Indifférent</option>
            @foreach(['50000000'=>'50 M','80000000'=>'80 M','150000000'=>'150 M','300000000'=>'300 M +'] as $v=>$lbl)
              <option value="{{ $v }}" @selected(request('budget')==$v)>{{ $lbl }} FCFA</option>
            @endforeach
          </select>
        </div>
        <button type="submit">Filtrer</button>
      </form>
      @if(request('q')||request('type')||request('budget'))
        <a class="reset" href="{{ route('biens') }}">Réinitialiser</a>
      @endif
    </div>

    <div class="count-line"><b>{{ $biens->count() }}</b> bien{{ $biens->count()>1?'s':'' }} {{ (request('q')||request('type')||request('budget')) ? 'correspondant à votre recherche' : 'au catalogue' }}.</div>

    <div class="props">
      @forelse($biens as $b)
        <div class="pcard" data-reveal>
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
            </div>
            <div style="display:flex;align-items:center;gap:8px;font-size:13px;color:var(--muted);margin-top:14px">
              <span style="width:8px;height:8px;border-radius:50%;background:{{ $b->cloture_incluse ? '#3f9d6b' : 'var(--orange)' }}"></span>
              {{ $b->clotureLabel() }}@unless($b->cloture_incluse) · <span style="color:var(--orange2);font-weight:600">clôture +{{ number_format((float)$b->cloture_prix/1000000,1,',',' ') }} M</span>@endunless
            </div>
            <div class="pf">
              <div class="price">{{ number_format((float)$b->price,0,',',' ') }}<small>FCFA · apport {{ number_format($b->apportInitial()/1000000,1,',',' ') }} M</small></div>
            </div>
            <a class="btn btn-ink" href="{{ route('register.create') }}" style="width:100%;margin-top:18px">Adhérer à ce bien <span>→</span></a>
          </div>
        </div>
      @empty
        <div class="empty">
          <h3>Aucun bien ne correspond</h3>
          <p>Modifiez vos critères ou consultez l'ensemble du catalogue.</p>
          <a class="btn btn-outline" href="{{ route('biens') }}" style="margin-top:18px">Voir tous les biens</a>
        </div>
      @endforelse
    </div>
  </div>
</section>

<section class="cta-img">
  <div class="bg" style="background-image:url('{{ asset('image/slider1.jpeg') }}')"></div>
  <div class="ov"></div>
  <div class="inner" data-reveal>
    <h2>Un bien vous <em>intéresse</em> ?</h2>
    <p>Créez votre compte : un conseiller vous présente le bien, l'option clôture et l'échéancier adapté à votre capacité.</p>
    <a class="btn btn-orange" href="{{ route('register.create') }}">Créer un compte <span>→</span></a>
  </div>
</section>
@endsection
