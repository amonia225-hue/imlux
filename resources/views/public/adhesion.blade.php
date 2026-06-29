@extends('public.layout')
@section('title', "L'adhésion — Lorny Conseils Management")

@section('content')
<section class="page-banner">
  <div class="bg"></div><div class="ov"></div>
  <div class="wrap inner">
    <div class="crumb">Accueil — L'adhésion</div>
    <h1>Devenir <em>adhérent</em>.</h1>
    <p>De l'apport initial au titre de propriété, votre adhésion suit un parcours clair et encadré par le bureau d'études.</p>
  </div>
</section>

<section class="section">
  <div class="wrap">
    <div class="sec-eyebrow"><span class="eyebrow">Le parcours</span></div>
    <h2 class="sec-title">Trois étapes, <em>un titre</em>.</h2>
    <div class="svc-grid">
      <div class="svc"><div class="no">01</div><h4>Créez votre compte</h4><p>Inscription en ligne en quelques minutes, pièces d'identité à l'appui. Simple et confidentiel.</p></div>
      <div class="svc"><div class="no">02</div><h4>Validation par le bureau d'études</h4><p>Un conseiller vérifie votre dossier puis active votre espace membre. Vous êtes notifié dès l'activation.</p></div>
      <div class="svc"><div class="no">03</div><h4>Choisissez &amp; adhérez</h4><p>Versez votre apport de 35 %, puis réglez le solde selon l'échéancier : mensuel ou trimestriel.</p></div>
    </div>
  </div>
</section>

<section class="section split light" style="padding-top:0">
  <div class="wrap">
    <div>
      <div class="sec-eyebrow"><span class="eyebrow">La règle des 35 %</span></div>
      <h2 class="sec-title">Un apport qui <em>sécurise</em>.</h2>
      <p class="body" style="color:#4a5468">Un apport initial de 35 % du montant du bien est requis avant le démarrage des échéances. Cette règle protège votre engagement comme le nôtre, et déclenche votre échéancier personnalisé une fois le seuil atteint.</p>
      <p class="body" style="color:#4a5468">Les frais d'ouverture de dossier (500 000 FCFA) sont distincts du prix du bien ; un reçu vous est délivré dès leur règlement.</p>
      <div style="margin-top:30px"><a class="btn btn-orange" href="{{ route('register.create') }}">Créer un compte <span>→</span></a></div>
    </div>
    <div class="img"></div>
  </div>
</section>

<section class="section">
  <div class="wrap">
    <div class="sec-eyebrow"><span class="eyebrow">Vos avantages</span></div>
    <h2 class="sec-title">Tout, <em>au même endroit</em>.</h2>
    <div class="svc-grid">
      <div class="svc"><div class="no">·</div><h4>Échéancier en temps réel</h4><p>Suivez vos versements, votre reste à payer et vos prochaines échéances depuis votre espace.</p></div>
      <div class="svc"><div class="no">·</div><h4>Suivi des travaux</h4><p>L'avancement du chantier par niveau, avec photos, et une notification à chaque étape franchie.</p></div>
      <div class="svc"><div class="no">·</div><h4>Documents officiels</h4><p>Factures, attestations de paiement et reçus de frais disponibles à tout moment, en PDF.</p></div>
    </div>
  </div>
</section>

<section class="section cta-band light" style="padding-top:96px">
  <div class="wrap">
    <div class="sec-eyebrow c"><span class="eyebrow">Votre projet</span></div>
    <h2>Prêt à <em>adhérer</em> ?</h2>
    <p>Créez votre compte aujourd'hui. Un conseiller Lorny vous accompagne dès la validation.</p>
    <a class="btn btn-orange" href="{{ route('register.create') }}">Créer un compte <span>→</span></a>
  </div>
</section>
@endsection
