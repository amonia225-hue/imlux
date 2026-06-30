@extends('public.layout')
@section('title', 'FAQ — Lorny Conseils Management')
@section('meta_description', "Questions fréquentes sur l'adhésion, l'apport de 35 %, les frais de dossier, le suivi des travaux et les documents chez Lorny Conseils Management.")

@section('content')
<section class="page-head">
  <div class="wrap">
    <div class="crumb">Accueil — FAQ</div>
    <h1>Questions <em>fréquentes</em></h1>
    <p>Tout ce qu'il faut savoir avant d'adhérer à un programme Lorny Conseils Management.</p>
  </div>
</section>

<section class="section cream">
  <div class="wrap">
    <div class="faqwrap">
      @foreach($faqs as $f)
        <div class="fitem"><button class="fq" type="button"><span>{{ $f['q'] }}</span><span class="ic">+</span></button><div class="fa"><p>{{ $f['a'] }}</p></div></div>
      @endforeach
    </div>
  </div>
</section>

<section class="cta-img">
  <div class="bg" style="background-image:url('{{ asset('image/slider1.jpeg') }}')"></div>
  <div class="ov"></div>
  <div class="inner" data-reveal>
    <h2>Une autre <em>question</em> ?</h2>
    <p>Notre équipe vous répond et vous accompagne à chaque étape de votre adhésion.</p>
    <a class="btn btn-orange" href="{{ route('contact') }}">Nous contacter <span>→</span></a>
  </div>
</section>
@endsection

@push('scripts')
@php
  $faqLd = [
    '@context' => 'https://schema.org',
    '@type' => 'FAQPage',
    'mainEntity' => collect($faqs)->map(fn ($f) => [
      '@type' => 'Question',
      'name' => $f['q'],
      'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f['a']],
    ])->all(),
  ];
@endphp
<script type="application/ld+json">{!! json_encode($faqLd, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
@endpush
