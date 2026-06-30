@extends('public.layout')
@section('title', 'Contact — Lorny Conseils Management')
@section('meta_description', "Contactez Lorny Conseils Management à Abidjan : un conseiller du cabinet étudie votre projet immobilier en toute confidentialité. WhatsApp, email, formulaire.")

@section('styles')
.cgrid{display:grid;grid-template-columns:.9fr 1.1fr;gap:64px;align-items:start}
.cinfo .row{display:flex;gap:16px;padding:24px 0;border-bottom:1px solid var(--line)}
.cinfo .row:first-of-type{padding-top:0}
.cinfo .ic{flex:0 0 auto;width:46px;height:46px;border-radius:12px;background:var(--paper2);border:1px solid var(--line);display:flex;align-items:center;justify-content:center;color:var(--orange2);font-size:18px}
.cinfo .k{font-size:11px;letter-spacing:.12em;text-transform:uppercase;color:var(--orange2);font-weight:600}
.cinfo .v{font-family:var(--serif);font-weight:600;font-size:19px;color:var(--navy);margin-top:4px}
.cinfo .v small{display:block;font-family:var(--sans);font-weight:400;font-size:13.5px;color:var(--muted);margin-top:3px}
.cform{background:#fff;border:1px solid var(--line);border-radius:10px;padding:40px 38px}
.cform h3{font-family:var(--serif);font-weight:600;font-size:26px;color:var(--navy)}
.cform .lead{color:var(--muted);font-size:14.5px;margin:8px 0 24px}
.cgridf{display:grid;grid-template-columns:1fr 1fr;gap:14px}
.field{margin-bottom:14px}.field.full{grid-column:1/-1}
.cform label{display:block;font-size:11px;letter-spacing:.08em;text-transform:uppercase;color:var(--muted2);font-weight:600;margin-bottom:6px}
.cform input,.cform textarea{width:100%;padding:13px 15px;border:1px solid var(--line);border-radius:8px;background:var(--paper2);font:inherit;font-size:14.5px;color:var(--ink);transition:.18s}
.cform input:focus,.cform textarea:focus{outline:none;border-color:var(--blue2);box-shadow:0 0 0 3px rgba(53,102,214,.13);background:#fff}
.cform textarea{min-height:130px;resize:vertical}
.ok{background:#eef7f1;border:1px solid #c4e3d1;border-radius:10px;padding:24px;text-align:center}
.ok .ic{width:50px;height:50px;border-radius:50%;background:var(--orange);color:#fff;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;font-size:26px}
.ok h4{font-family:var(--serif);font-weight:600;font-size:22px;color:var(--navy);margin-bottom:6px}
.ok p{color:var(--muted);font-size:14.5px}
.errs{background:#fdecec;border:1px solid #f5c2c2;color:#a23131;border-radius:10px;padding:12px 14px;margin-bottom:16px;font-size:13.5px}
.errs ul{margin:0;padding-left:18px}
@media(max-width:960px){.cgrid{grid-template-columns:1fr;gap:42px}.cgridf{grid-template-columns:1fr}}
@endsection

@section('content')
<section class="page-head">
  <div class="wrap">
    <div class="crumb">Accueil — Contact</div>
    <h1>Nous <em>contacter</em></h1>
    <p>Un conseiller du cabinet vous reçoit en toute confidentialité pour étudier votre projet immobilier.</p>
  </div>
</section>

<section class="section">
  <div class="wrap cgrid">
    <div class="cinfo" data-reveal>
      <div class="eyebrow">Le cabinet</div>
      <h2 class="sec-title" style="margin:14px 0 30px">Restons en <em>contact.</em></h2>
      <div class="row"><div class="ic">📍</div><div><div class="k">Adresse</div><div class="v">Cocody, Abidjan<small>Côte d'Ivoire</small></div></div></div>
      <div class="row"><div class="ic">📱</div><div><div class="k">Téléphone / WhatsApp</div><div class="v"><a href="https://wa.me/2250545870606" target="_blank" rel="noopener" style="color:inherit">+225 05 45 87 06 06</a><small>Disponible sur WhatsApp · Lun – Ven, 8h – 18h</small></div></div></div>
      <div class="row"><div class="ic">✉️</div><div><div class="k">Email</div><div class="v"><a href="mailto:contact@lornyconseils.com" style="color:inherit">contact@lornyconseils.com</a></div></div></div>
    </div>

    <div class="cform" data-reveal>
      @if(session('contact_sent'))
        <div class="ok">
          <div class="ic">✓</div>
          <h4>Message envoyé</h4>
          <p>Merci. Un conseiller du cabinet vous recontacte dans les meilleurs délais.</p>
        </div>
      @else
        <h3>Écrivez-nous</h3>
        <p class="lead">Renseignez vos coordonnées et votre besoin : nous vous répondons rapidement.</p>
        @if($errors->any())
          <div class="errs"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif
        <form method="POST" action="{{ route('contact.send') }}">
          @csrf
          <div class="cgridf">
            <div class="field"><label>Nom complet *</label><input name="name" value="{{ old('name') }}" required></div>
            <div class="field"><label>Téléphone *</label><input name="phone" value="{{ old('phone') }}" placeholder="+225 ..." required></div>
            <div class="field"><label>Email</label><input name="email" type="email" value="{{ old('email') }}"></div>
            <div class="field"><label>Sujet</label><input name="subject" value="{{ old('subject') }}" placeholder="Ex. Adhésion, information bien…"></div>
            <div class="field full"><label>Votre message *</label><textarea name="message" required>{{ old('message') }}</textarea></div>
          </div>
          <button class="btn btn-orange" type="submit" style="width:100%;margin-top:6px">Envoyer le message <span>→</span></button>
        </form>
      @endif
    </div>
  </div>
</section>

<section class="cta-img">
  <div class="bg" style="background-image:url('{{ asset('image/slider1.jpeg') }}')"></div>
  <div class="ov"></div>
  <div class="inner" data-reveal>
    <h2>Bâtissons votre <em>patrimoine</em></h2>
    <p>De la première question à la remise de votre titre, nous vous accompagnons à chaque étape.</p>
    <a class="btn btn-orange" href="{{ route('register.create') }}">Démarrer mon adhésion <span>→</span></a>
  </div>
</section>
@endsection
