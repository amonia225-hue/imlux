@extends('public.layout')
@section('title', 'Application mobile — Lorny Conseils Management')
@section('meta_description', "L'application mobile Lorny : suivez vos versements, vos reçus, l'avancement de votre logement et recevez vos notifications, où que vous soyez.")

@section('styles')
.appgrid{display:grid;grid-template-columns:1.05fr .95fr;gap:64px;align-items:center}
.appfeat{display:flex;gap:16px;padding:20px 0;border-bottom:1px solid var(--line)}
.appfeat:last-child{border-bottom:none}
.appfeat .ic{flex:0 0 auto;width:46px;height:46px;border-radius:12px;background:linear-gradient(135deg,var(--orange),var(--orange2));color:#fff;display:flex;align-items:center;justify-content:center;font-size:20px}
.appfeat .t{font-family:var(--serif);font-weight:600;font-size:19px;color:var(--navy)}
.appfeat .d{color:var(--muted);font-size:14.5px;margin-top:3px;line-height:1.6}
.appcta{background:var(--ink);border-radius:10px;padding:42px 36px;text-align:center;color:#fff}
.appcta .count{font-family:var(--serif);font-weight:700;font-size:54px;color:var(--orange-soft);line-height:1}
.appcta .clabel{font-size:11px;letter-spacing:.14em;text-transform:uppercase;color:rgba(255,255,255,.6);margin-top:6px}
.appcta .note{color:rgba(255,255,255,.6);font-size:13px;margin-top:18px;line-height:1.6}
.appcta .soon{color:var(--orange-soft);font-size:13px;border:1px dashed var(--line-d);border-radius:12px;padding:16px;margin-top:24px}
@media(max-width:960px){.appgrid{grid-template-columns:1fr;gap:38px}}
@endsection

@section('content')
<section class="page-head">
  <div class="wrap">
    <div class="crumb">Accueil — Application mobile</div>
    <h1>L'app <em>Lorny</em> dans votre poche</h1>
    <p>Suivez vos versements, vos reçus, l'avancement de votre logement et recevez vos notifications, où que vous soyez.</p>
  </div>
</section>

<section class="section">
  <div class="wrap appgrid">
    <div data-reveal>
      <div class="eyebrow">Application Android</div>
      <h2 class="sec-title" style="margin:14px 0 24px">Votre espace adhérent, <em>partout.</em></h2>
      <div class="appfeat"><div class="ic">🔑</div><div><div class="t">Vos biens &amp; échéances</div><div class="d">Montant payé, reste à payer, échéancier et statut de chaque adhésion.</div></div></div>
      <div class="appfeat"><div class="ic">📷</div><div><div class="t">Avancement des travaux en photos</div><div class="d">Suivez le chantier niveau par niveau, avec les vraies photos publiées par le cabinet.</div></div></div>
      <div class="appfeat"><div class="ic">🧾</div><div><div class="t">Factures &amp; reçus</div><div class="d">Téléchargez vos factures et accédez aux reçus de paiement déposés par l'administration.</div></div></div>
      <div class="appfeat"><div class="ic">🔔</div><div><div class="t">Notifications en temps réel</div><div class="d">Versement enregistré, étape de chantier franchie, nouveau bien disponible : vous êtes prévenu.</div></div></div>
    </div>

    <div class="appcta" data-reveal>
      @if (session('error'))
        <p style="color:#F4B25E;font-size:14px;margin-bottom:16px">{{ session('error') }}</p>
      @endif
      <div class="count">{{ number_format($downloads, 0, ',', ' ') }}</div>
      <div class="clabel">Téléchargements</div>
      @if ($available)
        <div style="margin-top:26px">
          <a class="btn btn-orange" href="{{ route('app.download') }}" style="width:100%">Télécharger l'application <span>↓</span></a>
        </div>
        <p class="note">Android · fichier .apk · gratuit.<br>À l'installation, autorisez « sources inconnues » si demandé.</p>
      @else
        <div class="soon">📱 Application bientôt disponible au téléchargement.</div>
      @endif
    </div>
  </div>
</section>
@endsection
