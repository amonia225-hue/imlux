@extends('public.layout')
@section('title', 'FAQ — Lorny Conseils Management')

@section('content')
<section class="page-banner">
  <div class="bg"></div><div class="ov"></div>
  <div class="wrap inner">
    <div class="crumb">Accueil — FAQ</div>
    <h1>Questions <em>fréquentes</em>.</h1>
    <p>Tout ce qu'il faut savoir avant d'adhérer à un programme Lorny Conseils Management.</p>
  </div>
</section>

<section class="section light">
  <div class="wrap">
    <div class="faqwrap" style="margin-top:0">
      @foreach($faqs as $f)
        <div class="fitem"><button class="fq" type="button"><span>{{ $f['q'] }}</span><span class="ic">+</span></button><div class="fa"><p>{{ $f['a'] }}</p></div></div>
      @endforeach
    </div>
  </div>
</section>

<section class="section cta-band">
  <div class="wrap">
    <div class="sec-eyebrow c"><span class="eyebrow">Une autre question ?</span></div>
    <h2>Parlons de votre <em>projet</em>.</h2>
    <p>Notre équipe vous répond et vous accompagne à chaque étape de votre adhésion.</p>
    <a class="btn btn-orange" href="{{ route('contact') }}">Nous contacter <span>→</span></a>
  </div>
</section>
@endsection
