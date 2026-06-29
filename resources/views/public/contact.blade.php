@extends('public.layout')
@section('title', 'Contact — Lorny Conseils Management')

@section('styles')
.cgrid{display:grid;grid-template-columns:1fr 1fr;gap:80px;align-items:start}
.cinfo .row{display:flex;gap:18px;padding:26px 0;border-bottom:1px solid var(--line)}
.cinfo .row:first-child{padding-top:0}
.cinfo .ic{flex:0 0 auto;width:46px;height:46px;border-radius:11px;border:1px solid var(--line);display:flex;align-items:center;justify-content:center;color:var(--orange);font-family:var(--mono);font-size:14px}
.cinfo .k{font-family:var(--mono);font-size:11px;letter-spacing:.12em;text-transform:uppercase;color:var(--orange-soft)}
.cinfo .v{font-family:var(--serif);font-weight:500;font-size:20px;color:var(--light);margin-top:4px}
.cinfo .v small{display:block;font-family:var(--sans);font-weight:300;font-size:14px;color:var(--light2);margin-top:4px}
.cpanel{background:var(--bg2);border:1px solid var(--line);padding:46px 44px}
.cpanel h3{font-family:var(--serif);font-weight:500;font-size:28px;color:var(--light)}
.cpanel p{color:var(--light2);font-weight:300;margin:14px 0 28px}
.cpanel .ll{display:flex;flex-direction:column;gap:14px}
@media(max-width:960px){.cgrid{grid-template-columns:1fr;gap:48px}}
@endsection

@section('content')
<section class="page-banner">
  <div class="bg"></div><div class="ov"></div>
  <div class="wrap inner">
    <div class="crumb">Accueil — Contact</div>
    <h1>Nous <em>contacter</em>.</h1>
    <p>Un conseiller du bureau d'études vous reçoit en toute confidentialité pour étudier votre projet.</p>
  </div>
</section>

<section class="section">
  <div class="wrap cgrid">
    <div class="cinfo">
      <div class="sec-eyebrow"><span class="eyebrow">Le bureau d'études</span></div>
      <h2 class="sec-title" style="margin-bottom:36px">Restons en <em>contact</em>.</h2>
      <div class="row"><div class="ic">◷</div><div><div class="k">Adresse</div><div class="v">Cocody, Abidjan<small>Côte d'Ivoire</small></div></div></div>
      <div class="row"><div class="ic">☏</div><div><div class="k">Téléphone / WhatsApp</div><div class="v"><a href="https://wa.me/2250545870606" target="_blank" rel="noopener" style="color:inherit">+225 05 45 87 06 06</a><small>Disponible sur WhatsApp · Lun – Ven, 8h – 18h</small></div></div></div>
      <div class="row"><div class="ic">✉</div><div><div class="k">Email</div><div class="v"><a href="mailto:contact@imlux.ci" style="color:inherit">contact@imlux.ci</a></div></div></div>
    </div>
    <div class="cpanel">
      <h3>Créez votre compte</h3>
      <p>Le plus simple pour démarrer : créez votre compte en ligne. Un conseiller valide votre dossier et vous recontacte pour la suite.</p>
      <div class="ll">
        <a class="btn btn-orange" href="{{ route('register.create') }}" style="justify-content:center">Créer un compte <span>→</span></a>
        <a class="btn btn-blue" href="{{ route('consultation.index') }}" style="justify-content:center">Accéder à mon espace</a>
      </div>
    </div>
  </div>
</section>

<section class="section cta-band light" style="padding-top:96px">
  <div class="wrap">
    <div class="sec-eyebrow c"><span class="eyebrow">À votre écoute</span></div>
    <h2>Bâtissons votre <em>patrimoine</em>.</h2>
    <p>De la première question à la remise de votre titre, nous vous accompagnons.</p>
    <a class="btn btn-orange" href="{{ route('register.create') }}">Démarrer mon adhésion <span>→</span></a>
  </div>
</section>
@endsection
