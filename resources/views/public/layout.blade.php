@php
  $logo = asset('image/lorny.png');
  $bannerBien = \App\Models\Bien::whereNotNull('photo')->orderBy('ordre')->orderBy('id')->first();
  $hero = $bannerBien ? asset('storage/'.$bannerBien->photo) : asset('image/banner-arch.svg');
@endphp
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title', 'Lorny Conseils Management') — Bureau d'études de gestion immobilière à Abidjan</title>
<link rel="icon" type="image/png" href="{{ $logo }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,700;0,800;1,500;1,700&family=Jost:wght@300;400;500;600&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
<style>
:root{
  --bg:#0B1426; --bg2:#0F1C36; --blue:#1E40AF; --blue2:#3B5BDB; --blue-soft:#7FA0E8;
  --orange:#ED8B1C; --orange2:#C9710E; --orange-soft:#F4B25E;
  --light:#ECEFF5; --light2:#B7C1D6; --muted:#7E8BA6; --line:rgba(234,238,246,.13);
  --serif:'Playfair Display',serif; --sans:'Jost',sans-serif; --mono:'Space Mono',monospace;
}
*{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth}
body{font-family:var(--sans);background:var(--bg);color:var(--light);-webkit-font-smoothing:antialiased;line-height:1.7;font-weight:300}
.wrap{max-width:1320px;margin:0 auto;padding:0 48px}
a{text-decoration:none;color:inherit}
.eyebrow{font-family:var(--sans);font-weight:500;font-size:12px;letter-spacing:.32em;text-transform:uppercase;color:var(--orange)}
.mono{font-family:var(--mono)}
.btn{font-family:var(--sans);font-weight:500;font-size:13px;letter-spacing:.14em;text-transform:uppercase;padding:17px 28px;cursor:pointer;border:1px solid transparent;transition:.3s;display:inline-flex;align-items:center;gap:11px}
.btn-orange{background:var(--orange);color:#1d1206}.btn-orange:hover{background:var(--orange-soft)}
.btn-outline{background:transparent;color:var(--light);border-color:rgba(236,239,245,.4)}.btn-outline:hover{border-color:var(--orange);color:var(--orange)}
.btn-ghost{background:transparent;color:var(--orange);border:1px solid var(--orange)}.btn-ghost:hover{background:var(--orange);color:#1d1206}
.btn-blue{background:var(--blue);color:#fff;border-color:var(--blue);box-shadow:0 10px 24px rgba(30,64,175,.32)}.btn-blue:hover{background:var(--blue2);border-color:var(--blue2)}

header{position:fixed;top:0;left:0;right:0;z-index:50;transition:.4s;background:linear-gradient(180deg,rgba(8,14,26,.7),rgba(8,14,26,0))}
header.solid{background:rgba(9,16,30,.94);backdrop-filter:blur(12px);border-bottom:1px solid var(--line)}
.nav{display:flex;align-items:center;justify-content:space-between;height:96px;transition:.4s}
header.solid .nav{height:76px}
.logo{display:flex;align-items:center;gap:14px}
.logo .chip{height:48px;width:48px;border-radius:13px;background:#fff;display:flex;align-items:center;justify-content:center;flex:0 0 auto;transition:.3s}
header.solid .logo .chip{height:42px;width:42px}
.logo .chip img{height:38px;width:38px;object-fit:contain}
.logo .bn{font-family:var(--serif);font-weight:700;font-size:23px;line-height:1.04;color:var(--light)}
.logo .bn small{display:block;font-family:var(--sans);font-weight:400;font-size:10px;letter-spacing:.24em;text-transform:uppercase;color:var(--orange-soft);margin-top:2px}
.menu{display:flex;gap:40px;font-size:14px;font-weight:400;letter-spacing:.03em;color:var(--light)}
.menu a{position:relative;padding:4px 0}.menu a::after{content:"";position:absolute;left:0;bottom:-2px;width:0;height:1px;background:var(--orange);transition:.3s}
.menu a:hover{color:var(--orange)}.menu a:hover::after{width:100%}
.menu a.active{color:var(--orange)}.menu a.active::after{width:100%}

/* page banner (pages internes) — bandeau image en entête */
.page-banner{position:relative;min-height:clamp(360px,44vw,480px);display:flex;align-items:flex-end;padding:160px 0 64px;overflow:hidden;background:#0a1120}
.page-banner::before{content:"";position:absolute;left:0;right:0;bottom:0;height:4px;z-index:3;background:linear-gradient(90deg,var(--blue) 0%,var(--blue2) 38%,var(--orange) 100%)}
.page-banner .bg{position:absolute;inset:-4% -2%;background:var(--banner,url('{{ $hero }}')) center 38%/cover no-repeat,linear-gradient(120deg,#16315f,#0a1120);transform:scale(1.06);will-change:transform}
.page-banner .ov{position:absolute;inset:0;background:
  linear-gradient(180deg,rgba(7,12,24,.5) 0%,rgba(7,12,24,.34) 38%,rgba(7,12,24,.78) 78%,var(--bg) 100%),
  linear-gradient(98deg,rgba(7,12,24,.9) 0%,rgba(7,12,24,.4) 50%,rgba(7,12,24,0) 82%)}
.page-banner::after{content:"";position:absolute;top:-12%;right:-6%;width:46%;height:120%;z-index:1;pointer-events:none;
  background:radial-gradient(closest-side,rgba(237,139,28,.22),transparent 72%)}
.page-banner .inner{position:relative;z-index:2;width:100%}
.page-banner .crumb{display:inline-flex;align-items:center;gap:14px;font-family:var(--mono);font-size:12px;letter-spacing:.16em;text-transform:uppercase;color:var(--orange-soft);margin-bottom:22px}
.page-banner .crumb::before{content:"";width:36px;height:1px;background:var(--orange)}
.page-banner h1{font-family:var(--serif);font-weight:700;font-size:clamp(42px,6.4vw,82px);line-height:1.0;letter-spacing:-.015em;color:var(--light);text-shadow:0 8px 40px rgba(0,0,0,.4)}
.page-banner h1 em{font-style:italic;color:var(--orange)}
.page-banner p{color:var(--light2);font-weight:300;font-size:18px;max-width:56ch;margin-top:20px;line-height:1.75}

.section{padding:118px 0}
.sec-eyebrow{display:flex;align-items:center;gap:16px;margin-bottom:20px}
.sec-eyebrow::before{content:"";width:46px;height:1px;background:var(--orange)}
.sec-eyebrow.c{justify-content:center}
.sec-title{font-family:var(--serif);font-weight:500;font-size:clamp(32px,4.4vw,54px);line-height:1.08;letter-spacing:-.01em;color:var(--light)}
.sec-title em{font-style:italic;color:var(--orange)}
.lead{color:var(--light2);font-weight:300;font-size:17px;max-width:54ch;line-height:1.85;margin-top:18px}

.intro{border-top:1px solid var(--line);border-bottom:1px solid var(--line);background:var(--bg2)}
.intro .wrap{display:grid;grid-template-columns:1.2fr .8fr;gap:80px;padding:100px 48px;align-items:center}
.stats{display:grid;grid-template-columns:1fr 1fr;gap:44px 30px}
.stat .n{font-family:var(--serif);font-weight:700;font-size:50px;color:var(--orange);line-height:1}
.stat:nth-child(even) .n{color:var(--blue-soft)}
.stat .l{font-family:var(--mono);font-size:11px;letter-spacing:.08em;color:var(--light2);margin-top:10px;text-transform:uppercase}

.head-row{display:flex;align-items:flex-end;justify-content:space-between;gap:30px;margin-bottom:56px}
.props{display:grid;grid-template-columns:repeat(3,1fr);gap:28px}
.pcard{position:relative;overflow:hidden;background:#13213c;border:1px solid var(--line);min-height:380px;display:flex;flex-direction:column}
.pcard .img{height:230px;background:url('{{ $hero }}') center/cover,linear-gradient(135deg,#1c3a6e,#0c1730);position:relative;transition:transform .8s ease}
.pcard:hover .img{transform:scale(1.06)}
.pcard .img::after{content:"";position:absolute;inset:0;background:linear-gradient(180deg,rgba(11,20,38,.15) 30%,rgba(11,20,38,.86))}
.pcard .tag{position:absolute;top:16px;left:16px;z-index:2;font-family:var(--mono);font-size:10px;letter-spacing:.12em;text-transform:uppercase;color:var(--orange);border:1px solid var(--orange);padding:6px 11px}
.pcard .nm{position:absolute;left:22px;right:22px;top:148px;z-index:2}
.pcard .loc{font-size:12px;letter-spacing:.14em;text-transform:uppercase;color:var(--orange-soft)}
.pcard .nm h3{font-family:var(--serif);font-weight:500;font-size:25px;color:var(--light);margin-top:5px}
.pcard .body{padding:24px 22px 22px;margin-top:auto}
.pcard .specs{display:flex;gap:22px}
.pcard .specs .k{font-family:var(--mono);font-size:10px;letter-spacing:.08em;text-transform:uppercase;color:var(--muted)}
.pcard .specs .v{font-family:var(--serif);font-weight:600;font-size:17px;color:var(--light);margin-top:3px}
.pcard .pf{display:flex;align-items:center;justify-content:space-between;border-top:1px solid var(--line);margin-top:18px;padding-top:16px}
.pcard .apport{font-family:var(--mono);font-size:12px;color:var(--orange-soft)}
.pcard .adh{font-size:12px;letter-spacing:.12em;text-transform:uppercase;color:var(--light);border-bottom:1px solid var(--orange);padding-bottom:3px}

.svc-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:24px;margin-top:60px}
.svc{position:relative;padding:44px 34px 38px;border:1px solid var(--line);border-radius:20px;background:rgba(255,255,255,.02);transition:transform .3s,box-shadow .3s,border-color .3s}
.svc:hover{transform:translateY(-6px);border-color:var(--orange);box-shadow:0 20px 44px rgba(0,0,0,.3)}
.svc .no{display:inline-flex;align-items:center;justify-content:center;width:58px;height:58px;border-radius:50%;font-family:var(--serif);font-weight:700;font-size:22px;color:#fff;background:linear-gradient(135deg,var(--orange),var(--orange2));box-shadow:0 12px 26px rgba(237,139,28,.42);margin-bottom:24px}
.svc:nth-child(2) .no{background:linear-gradient(135deg,var(--blue2),var(--blue));box-shadow:0 12px 26px rgba(30,58,140,.45)}
.svc:nth-child(3) .no{background:linear-gradient(135deg,#26A66B,#177A4A);box-shadow:0 12px 26px rgba(23,122,74,.42)}
.svc h4{font-family:var(--serif);font-weight:600;font-size:24px;color:var(--light);margin:0 0 11px;letter-spacing:-.01em}
.svc p{font-size:15px;color:var(--light2);font-weight:300;line-height:1.8}

.split .wrap{display:grid;grid-template-columns:1fr 1fr;gap:80px;align-items:center}
.split .img{height:540px;background:url('{{ $hero }}') center/cover no-repeat,linear-gradient(135deg,#1c3a6e,#0c1730);position:relative;overflow:hidden;border:1px solid var(--line)}
.split .img::after{content:"";position:absolute;inset:0;background:linear-gradient(180deg,rgba(11,20,38,.2),rgba(11,20,38,.55))}
.split p.body{color:var(--light2);font-weight:300;font-size:16px;line-height:1.9;margin-top:22px}
.signoff{font-family:var(--serif);font-style:italic;font-size:22px;color:var(--orange);margin-top:30px}

.faqwrap{max-width:880px;margin:48px auto 0}
.fitem{border-bottom:1px solid var(--line)}
.fq{width:100%;text-align:left;background:none;border:none;cursor:pointer;padding:26px 4px;display:flex;align-items:center;justify-content:space-between;gap:18px;font-family:var(--serif);font-weight:500;font-size:20px;color:var(--light)}
.fq .ic{flex:0 0 auto;width:30px;height:30px;border-radius:50%;border:1px solid var(--line);display:flex;align-items:center;justify-content:center;color:var(--orange);transition:.25s;font-size:20px}
.fitem.open .fq .ic{transform:rotate(45deg);background:var(--orange);border-color:var(--orange);color:#1d1206}
.fa{max-height:0;overflow:hidden;transition:max-height .3s ease}
.fa p{padding:0 4px 26px;color:var(--light2);font-weight:300;font-size:15.5px;line-height:1.75;max-width:74ch}

.quote{text-align:center;background:var(--bg2);border-top:1px solid var(--line);border-bottom:1px solid var(--line)}
.quote .q{font-family:var(--serif);font-weight:500;font-size:clamp(26px,3.4vw,42px);line-height:1.32;color:var(--light);max-width:24ch;margin:24px auto 0}
.quote .q em{font-style:italic;color:var(--orange)}
.quote .who{margin-top:28px;font-family:var(--mono);font-size:12px;letter-spacing:.16em;text-transform:uppercase;color:var(--orange-soft)}

.cta-band{text-align:center}
.cta-band h2{font-family:var(--serif);font-weight:500;font-size:clamp(34px,5vw,62px);line-height:1.06;color:var(--light)}
.cta-band h2 em{font-style:italic;color:var(--orange)}
.cta-band p{color:var(--light2);font-weight:300;font-size:17px;margin:22px auto 38px;max-width:48ch}

/* sections claires */
section.light,.section.light{background:#F5F7FA;color:#33405c;border-top-color:#E6E9F0;border-bottom-color:#E6E9F0}
section.light .sec-title,.section.light .sec-title{color:#0d1a33}
section.light .sec-title em,.section.light .sec-title em{color:var(--orange2)}
section.light .eyebrow,.section.light .eyebrow{color:var(--orange2)}
section.light .lead,.section.light .lead{color:#34405a}
section.light .stat .n{color:var(--orange2)}
section.light .stat:nth-child(even) .n{color:var(--blue)}
section.light .stat .l{color:#5b647a}
section.light .svc{background:#fff;border-color:#E2E7F0;box-shadow:0 8px 22px rgba(11,20,38,.06)}
section.light .svc:hover{background:#fff;border-color:var(--orange);box-shadow:0 22px 44px rgba(11,20,38,.13)}
section.light .svc .no{color:#fff}
section.light .svc h4{color:#0B1426}
section.light .svc p{color:#3a4660}
.section.light .fitem{border-color:#E6E9F0}
.section.light .fq{color:#0d1a33}
.section.light .fq .ic{border-color:#dfe4ee;color:var(--blue)}
.section.light .fa p{color:#34405a}
.cta-band.light h2{color:#0d1a33}
.cta-band.light h2 em{color:var(--orange2)}
.cta-band.light p{color:#34405a}
.section.light .btn-outline,section.light .btn-outline{color:#0d1a33;border-color:#cfd6e2}
.section.light .btn-outline:hover,section.light .btn-outline:hover{border-color:var(--orange);color:var(--orange2)}
.section.light .split p.body{color:#34405a}

footer{background:linear-gradient(180deg,#1F46B8 0%,#16329B 52%,#0E2569 100%);border-top:4px solid var(--orange);padding:84px 0 36px}
.foot-grid{display:grid;grid-template-columns:1.4fr 1fr 1fr 1fr;gap:50px}
.foot p{color:#cdd8f4;font-weight:300;font-size:14.5px;max-width:34ch;margin-top:18px}
.foot h5{font-family:var(--sans);font-weight:600;font-size:12px;letter-spacing:.18em;text-transform:uppercase;color:var(--orange-soft);margin-bottom:20px}
.foot ul{list-style:none}.foot li{margin-bottom:12px}.foot a{color:#c3cfee;font-size:14.5px;transition:.2s}.foot a:hover{color:var(--orange)}
.foot-bottom{display:flex;justify-content:space-between;gap:20px;flex-wrap:wrap;border-top:1px solid rgba(255,255,255,.16);margin-top:60px;padding-top:28px;font-family:var(--mono);font-size:12px;color:rgba(255,255,255,.6)}

@media(max-width:960px){.menu{display:none}.wrap{padding:0 26px}.intro .wrap,.props,.svc-grid,.split .wrap,.foot-grid{grid-template-columns:1fr}.svc{border-right:none;border-bottom:1px solid var(--line)}}
@yield('styles')
</style>
</head>
<body>

<header id="hdr">
  <div class="wrap nav">
    <a class="logo" href="{{ route('home') }}"><span class="chip"><img src="{{ $logo }}" alt="LCM"></span><span class="bn">Lorny<small>Conseils Management</small></span></a>
    <nav class="menu">
      <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Accueil</a>
      <a href="{{ route('presentation') }}" class="{{ request()->routeIs('presentation') ? 'active' : '' }}">Le bureau d'études</a>
      <a href="{{ route('biens') }}" class="{{ request()->routeIs('biens') ? 'active' : '' }}">Nos offres</a>
      <a href="{{ route('adhesion') }}" class="{{ request()->routeIs('adhesion') ? 'active' : '' }}">L'adhésion</a>
      <a href="{{ route('faq') }}" class="{{ request()->routeIs('faq') ? 'active' : '' }}">FAQ</a>
      <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}">Contact</a>
    </nav>
    <a class="btn btn-ghost" href="{{ route('register.create') }}" style="padding:13px 22px">Créer un compte</a>
  </div>
</header>

@yield('content')

<footer>
  <div class="wrap">
    <div class="foot-grid">
      <div class="foot">
        <a class="logo" href="{{ route('home') }}"><span class="chip"><img src="{{ $logo }}" alt=""></span><span class="bn">Lorny<small>Conseils Management</small></span></a>
        <p><strong style="color:var(--light)">Projet IMLUX</strong> — l'accompagnement vers la propriété : programmes, échéanciers, travaux et titres.</p>
        <p style="margin-top:12px;font-family:var(--mono);font-size:11.5px;letter-spacing:.04em;color:var(--orange-soft)">Développé par <strong>AMONIA GROUP</strong></p>
      </div>
      <div class="foot"><h5>Explorer</h5><ul><li><a href="{{ route('presentation') }}">Le bureau d'études</a></li><li><a href="{{ route('biens') }}">Nos offres</a></li><li><a href="{{ route('adhesion') }}">L'adhésion</a></li><li><a href="{{ route('faq') }}">FAQ</a></li><li><a href="{{ route('contact') }}">Contact</a></li></ul></div>
      <div class="foot"><h5>Adhésion</h5><ul><li><a href="{{ route('register.create') }}">Créer un compte</a></li><li><a href="{{ route('login') }}">Espace membre</a></li><li><a href="{{ route('faq') }}">Apport 35 %</a></li></ul></div>
      <div class="foot"><h5>Contact</h5><ul><li><a href="{{ route('contact') }}">Abidjan, Côte d'Ivoire</a></li><li><a href="https://wa.me/2250545870606" target="_blank" rel="noopener">📱 +225 05 45 87 06 06 (WhatsApp)</a></li><li><a href="mailto:contact@imlux.ci">contact@imlux.ci</a></li></ul></div>
    </div>
    <div class="foot-bottom"><div>© {{ date('Y') }} Lorny Conseils Management — Abidjan</div><div>RCCM CI-ABJ · NCC</div></div>
  </div>
</footer>

{{-- Bouton flottant WhatsApp (toutes les pages publiques) --}}
<a href="https://wa.me/2250545870606?text={{ rawurlencode('Bonjour Lorny Conseils Management, je souhaite des informations.') }}"
   target="_blank" rel="noopener" aria-label="Écrire sur WhatsApp"
   style="position:fixed;right:22px;bottom:22px;z-index:90;display:inline-flex;align-items:center;justify-content:center;width:58px;height:58px;border-radius:50%;background:#25D366;box-shadow:0 10px 28px rgba(37,211,102,.45);transition:transform .2s"
   onmouseover="this.style.transform='scale(1.08)'" onmouseout="this.style.transform='scale(1)'">
  <svg viewBox="0 0 32 32" width="30" height="30" fill="#fff" aria-hidden="true"><path d="M16.04 3C9.4 3 4 8.4 4 15.04c0 2.12.55 4.18 1.6 6L4 29l8.2-2.15a12 12 0 0 0 3.84.63h.01C22.7 27.48 28 22.08 28 15.44 28 8.8 22.68 3 16.04 3zm0 21.9h-.01c-1.18 0-2.34-.32-3.35-.92l-.24-.14-4.87 1.28 1.3-4.75-.16-.25a9.94 9.94 0 0 1-1.52-5.27c0-5.5 4.48-9.98 9.99-9.98 2.67 0 5.18 1.04 7.06 2.93a9.92 9.92 0 0 1 2.92 7.06c0 5.5-4.48 9.98-9.99 9.98zm5.48-7.48c-.3-.15-1.78-.88-2.06-.98-.28-.1-.48-.15-.68.15-.2.3-.78.98-.95 1.18-.18.2-.35.22-.65.07-.3-.15-1.27-.47-2.42-1.49-.9-.8-1.5-1.78-1.67-2.08-.18-.3-.02-.46.13-.61.13-.13.3-.35.45-.52.15-.18.2-.3.3-.5.1-.2.05-.38-.02-.53-.08-.15-.68-1.63-.93-2.23-.24-.59-.49-.5-.68-.51l-.58-.01c-.2 0-.53.07-.8.38-.28.3-1.05 1.03-1.05 2.5 0 1.48 1.08 2.9 1.23 3.1.15.2 2.12 3.24 5.13 4.54.72.31 1.27.5 1.71.64.72.23 1.37.2 1.89.12.58-.09 1.78-.73 2.03-1.43.25-.7.25-1.3.18-1.43-.07-.13-.27-.2-.57-.35z"/></svg>
</a>

<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
<script>
const REDUCED=window.matchMedia('(prefers-reduced-motion: reduce)').matches;
document.querySelectorAll('.fitem').forEach(it=>{const q=it.querySelector('.fq'),a=it.querySelector('.fa');q.onclick=()=>{const open=it.classList.contains('open');document.querySelectorAll('.fitem.open').forEach(o=>{o.classList.remove('open');o.querySelector('.fa').style.maxHeight='0'});if(!open){it.classList.add('open');a.style.maxHeight=a.scrollHeight+'px';}};});
function init(){
  const hdr=document.getElementById('hdr');
  window.addEventListener('scroll',()=>hdr.classList.toggle('solid',window.scrollY>60),{passive:true});
  if(window.scrollY>60)hdr.classList.add('solid');
  if(REDUCED)return;
  gsap.registerPlugin(ScrollTrigger);
  if(document.getElementById('heroBg')){
    gsap.fromTo('#heroBg',{scale:1.14},{scale:1,duration:2.6,ease:'power2.out'});
    gsap.to('#heroBg',{yPercent:12,ease:'none',scrollTrigger:{trigger:'#hero',start:'top top',end:'bottom top',scrub:1}});
  }
  if(document.querySelector('.page-banner .bg')){
    gsap.fromTo('.page-banner .bg',{scale:1.14},{scale:1.06,duration:2.4,ease:'power2.out'});
    gsap.to('.page-banner .bg',{yPercent:16,ease:'none',scrollTrigger:{trigger:'.page-banner',start:'top top',end:'bottom top',scrub:1}});
  }
  const revealSel='.sec-title,.lead,.ab-lead,.stat,.pcard,.svc,.value,.checklist li,.fbien,.ab-frame,.split .img,.split p,.faqwrap,.quote .q,.cta-band h2,.cta-band p,.page-banner h1,.page-banner p';
  gsap.utils.toArray(revealSel).forEach(el=>gsap.from(el,{opacity:0,y:34,duration:.8,ease:'power3.out',scrollTrigger:{trigger:el,start:'top 96%',once:true}}));
  gsap.utils.toArray('.stat .n').forEach(el=>{const num=parseInt(el.textContent)||0,o={v:0};gsap.to(o,{v:num,duration:1.6,ease:'power1.out',scrollTrigger:{trigger:el,start:'top 95%',once:true},onUpdate:()=>el.textContent=Math.round(o.v).toLocaleString('fr-FR')});});
  ScrollTrigger.refresh();
  window.addEventListener('load',()=>ScrollTrigger.refresh());
  gsap.delayedCall(2.6,()=>document.querySelectorAll(revealSel).forEach(el=>{ if(parseFloat(getComputedStyle(el).opacity)<0.05) gsap.to(el,{opacity:1,y:0,duration:.4}); }));
}
if(document.readyState!=='loading')init();else document.addEventListener('DOMContentLoaded',init);
</script>
@stack('scripts')
</body>
</html>
