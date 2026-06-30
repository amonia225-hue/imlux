@extends('public.layout')
@section('title', "L'adhésion — Lorny Conseils Management")
@section('meta_description', "Comment devenir propriétaire avec Lorny Conseils Management : créez votre compte, validez votre dossier, versez un apport de 35 % puis réglez votre échéancier sur mesure.")

@section('styles')
.steps{display:grid;grid-template-columns:repeat(3,1fr);gap:24px}
.stepc{background:#fff;border:1px solid var(--line);border-radius:8px;padding:36px 32px;transition:transform .3s,box-shadow .3s}
.stepc:hover{transform:translateY(-6px);box-shadow:0 26px 50px -26px rgba(16,36,63,.32)}
.stepc .no{display:inline-flex;align-items:center;justify-content:center;width:50px;height:50px;border-radius:50%;background:var(--ink);color:#fff;font-family:var(--serif);font-weight:700;font-size:20px;margin-bottom:20px}
.stepc:nth-child(2) .no{background:var(--blue)}
.stepc:nth-child(3) .no{background:linear-gradient(135deg,var(--orange),var(--orange2))}
.stepc h3{font-family:var(--serif);font-weight:600;font-size:22px;color:var(--navy);margin-bottom:10px}
.stepc p{color:var(--muted);font-size:14.5px;line-height:1.7}
.rule{display:grid;grid-template-columns:1fr 1fr;gap:64px;align-items:center}
.rule .ph{height:440px;border-radius:8px;background:url('{{ asset('image/slider1.jpeg') }}') center/cover;position:relative;overflow:hidden}
.rule .ph::after{content:"";position:absolute;inset:0;background:linear-gradient(180deg,rgba(11,22,40,.1),rgba(11,22,40,.5))}
.rule .badge{position:absolute;left:24px;bottom:24px;z-index:2;background:#fff;border-radius:10px;padding:18px 22px;box-shadow:0 20px 40px -16px rgba(11,22,40,.5)}
.rule .badge .n{font-family:var(--serif);font-weight:700;font-size:38px;color:var(--orange2);line-height:1}
.rule .badge .l{font-size:11px;letter-spacing:.08em;text-transform:uppercase;color:var(--muted);margin-top:5px}
.adv{display:grid;grid-template-columns:repeat(3,1fr);gap:30px}
.advc{padding:30px 28px;border-top:2px solid var(--orange)}
.advc h4{font-family:var(--serif);font-weight:600;font-size:20px;color:var(--navy);margin-bottom:10px}
.advc p{color:var(--muted);font-size:14.5px;line-height:1.7}
@media(max-width:960px){.steps,.adv{grid-template-columns:1fr}.rule{grid-template-columns:1fr;gap:32px}.rule .ph{height:300px}}
@endsection

@section('content')
<section class="page-head">
  <div class="wrap">
    <div class="crumb">Accueil — L'adhésion</div>
    <h1>Devenir <em>adhérent</em></h1>
    <p>De l'apport initial au titre de propriété, votre adhésion suit un parcours clair et encadré par le Bureau d'études LCM.</p>
  </div>
</section>

<section class="section">
  <div class="wrap">
    <div class="sec-head" data-reveal>
      <div class="eyebrow">Le parcours</div>
      <h2 class="sec-title">Trois étapes, <em>un titre.</em></h2>
    </div>
    <div class="steps">
      <div class="stepc" data-reveal><div class="no">1</div><h3>Créez votre compte</h3><p>Inscription en ligne en quelques minutes, pièces d'identité à l'appui. Simple et confidentiel.</p></div>
      <div class="stepc" data-reveal><div class="no">2</div><h3>Validation du dossier</h3><p>Un conseiller vérifie votre dossier puis active votre espace membre. Vous êtes notifié dès l'activation.</p></div>
      <div class="stepc" data-reveal><div class="no">3</div><h3>Choisissez &amp; adhérez</h3><p>Versez votre apport de 35 %, puis réglez le solde selon l'échéancier : mensuel ou trimestriel.</p></div>
    </div>
  </div>
</section>

<section class="section cream">
  <div class="wrap rule">
    <div data-reveal>
      <div class="eyebrow">La règle des 35 %</div>
      <h2 class="sec-title" style="margin-top:14px">Un apport qui <em>sécurise.</em></h2>
      <p class="sec-sub">Un apport initial de 35 % du montant du bien est requis avant le démarrage des échéances. Cette règle protège votre engagement comme le nôtre, et déclenche votre échéancier personnalisé une fois le seuil atteint.</p>
      <p class="sec-sub">Les frais d'ouverture de dossier (500 000 FCFA) sont distincts du prix du bien ; un reçu vous est délivré dès leur règlement.</p>
      <div style="margin-top:28px"><a class="btn btn-orange" href="{{ route('register.create') }}">Créer un compte <span>→</span></a></div>
    </div>
    <div class="ph" data-reveal><div class="badge"><div class="n">35 %</div><div class="l">Apport pour démarrer</div></div></div>
  </div>
</section>

<section class="section">
  <div class="wrap">
    <div class="sec-head" data-reveal>
      <div class="eyebrow">Vos avantages</div>
      <h2 class="sec-title">Tout, <em>au même endroit.</em></h2>
    </div>
    <div class="adv">
      <div class="advc" data-reveal><h4>Échéancier en temps réel</h4><p>Suivez vos versements, votre reste à payer et vos prochaines échéances depuis votre espace.</p></div>
      <div class="advc" data-reveal><h4>Suivi des travaux</h4><p>L'avancement du chantier par niveau, avec photos, et une notification à chaque étape franchie.</p></div>
      <div class="advc" data-reveal><h4>Documents officiels</h4><p>Factures, attestations de paiement et reçus de frais disponibles à tout moment, en PDF.</p></div>
    </div>
  </div>
</section>

<section class="cta-img">
  <div class="bg" style="background-image:url('{{ asset('image/slider1.jpeg') }}')"></div>
  <div class="ov"></div>
  <div class="inner" data-reveal>
    <h2>Prêt à <em>adhérer</em> ?</h2>
    <p>Créez votre compte aujourd'hui. Un conseiller Lorny vous accompagne dès la validation de votre dossier.</p>
    <a class="btn btn-orange" href="{{ route('register.create') }}">Créer un compte <span>→</span></a>
  </div>
</section>
@endsection
