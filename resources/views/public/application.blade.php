@extends('public.layout')
@section('title', "Application mobile — Lorny Conseils Management")

@section('styles')
.appgrid{display:grid;grid-template-columns:1fr 1fr;gap:70px;align-items:center}
.appfeat{display:flex;gap:16px;padding:20px 0;border-bottom:1px solid var(--line)}
.appfeat:last-child{border-bottom:none}
.appfeat .ic{flex:0 0 auto;width:46px;height:46px;border-radius:11px;background:linear-gradient(135deg,var(--orange),var(--orange2));color:#1d1206;display:flex;align-items:center;justify-content:center;font-size:20px}
.appfeat .t{font-family:var(--serif);font-weight:600;font-size:19px;color:var(--light)}
.appfeat .d{color:var(--light2);font-weight:300;font-size:14.5px;margin-top:3px;line-height:1.6}
.appcta{background:var(--bg2);border:1px solid var(--line);border-radius:18px;padding:40px 36px;text-align:center}
.appcta .count{font-family:var(--serif);font-weight:700;font-size:54px;color:var(--orange);line-height:1}
.appcta .clabel{font-family:var(--mono);font-size:11px;letter-spacing:.12em;text-transform:uppercase;color:var(--light2);margin-top:6px}
.appcta .note{color:var(--light2);font-weight:300;font-size:13px;margin-top:18px}
.appcta .soon{color:var(--orange-soft);font-family:var(--mono);font-size:13px;border:1px dashed var(--line);border-radius:12px;padding:16px}
@media(max-width:960px){.appgrid{grid-template-columns:1fr;gap:40px}}
@endsection

@section('content')
<section class="page-banner">
  <div class="bg"></div><div class="ov"></div>
  <div class="wrap inner">
    <div class="crumb">Accueil — Application mobile</div>
    <h1>L'app <em>IMLUX</em> dans votre poche.</h1>
    <p>Suivez vos versements, vos reçus, l'avancement de votre logement et recevez vos notifications, où que vous soyez.</p>
  </div>
</section>

<section class="section">
  <div class="wrap appgrid">
    <div>
      <div class="sec-eyebrow"><span class="eyebrow">Application Android</span></div>
      <h2 class="sec-title" style="margin-bottom:18px">Votre espace adhérent, <em>partout</em>.</h2>
      <div class="appfeat"><div class="ic">🔑</div><div><div class="t">Vos biens &amp; échéances</div><div class="d">Montant payé, reste à payer, échéancier et statut de chaque adhésion.</div></div></div>
      <div class="appfeat"><div class="ic">📷</div><div><div class="t">Avancement des travaux en photos</div><div class="d">Suivez le chantier niveau par niveau, avec les vraies photos publiées par le cabinet.</div></div></div>
      <div class="appfeat"><div class="ic">🧾</div><div><div class="t">Factures &amp; reçus</div><div class="d">Téléchargez vos factures et accédez aux reçus de paiement déposés par l'administration.</div></div></div>
      <div class="appfeat"><div class="ic">🔔</div><div><div class="t">Notifications en temps réel</div><div class="d">Versement enregistré, étape de chantier franchie, nouveau bien disponible : vous êtes prévenu.</div></div></div>
    </div>

    <div class="appcta">
      @if (session('error'))
        <p style="color:#E07A6B;font-size:14px;margin-bottom:16px">{{ session('error') }}</p>
      @endif

      <div class="count">{{ number_format($downloads, 0, ',', ' ') }}</div>
      <div class="clabel">Téléchargements</div>

      @if ($available)
        <div style="margin-top:26px">
          <a class="btn btn-orange" href="{{ route('app.download') }}"><i></i>Télécharger l'application <span>↓</span></a>
        </div>
        <p class="note">Android · fichier .apk · gratuit.<br>À l'installation, autorisez « sources inconnues » si demandé.</p>
      @else
        <div style="margin-top:26px" class="soon">📱 Application bientôt disponible au téléchargement.</div>
      @endif
    </div>
  </div>
</section>
@endsection
