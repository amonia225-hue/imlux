@php
  $logo = asset('image/lorny.png');
  $bannerBien = \App\Models\Bien::whereNotNull('photo')->orderBy('ordre')->orderBy('id')->first();
  $ogImage = $bannerBien ? asset('storage/'.$bannerBien->photo) : asset('image/slider1.jpeg');
  $metaDesc = trim($__env->yieldContent('meta_description', "Lorny Conseils Management — bureau d'études de gestion immobilière à Abidjan. Acquisition et paiement échelonné, suivi de votre dossier en temps réel."));
@endphp
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title', 'Lorny Conseils Management') — Bureau d'études immobilier à Abidjan</title>
<meta name="description" content="{{ $metaDesc }}">
<meta name="theme-color" content="#10243F">
<link rel="canonical" href="{{ url()->current() }}">
<link rel="icon" type="image/png" href="{{ $logo }}">

{{-- Open Graph / partage WhatsApp & Facebook --}}
<meta property="og:type" content="website">
<meta property="og:site_name" content="Lorny Conseils Management">
<meta property="og:title" content="@yield('title', 'Lorny Conseils Management')">
<meta property="og:description" content="{{ $metaDesc }}">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:image" content="{{ $ogImage }}">
<meta property="og:locale" content="fr_FR">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="@yield('title', 'Lorny Conseils Management')">
<meta name="twitter:description" content="{{ $metaDesc }}">
<meta name="twitter:image" content="{{ $ogImage }}">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600&family=Manrope:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
:root{
  /* Épuré Lorny — base claire éditoriale, accent ORANGE */
  --paper:#F6F5F0;        /* fond crème chaud */
  --paper2:#FBFAF6;       /* crème plus clair (sections) */
  --surface:#FFFFFF;
  --ink:#10243F;          /* navy profond Lorny (sections sombres) */
  --ink2:#0B1A30;         /* footer */
  --navy:#13243F;         /* texte titres sombres */
  --blue:#1E3A8C;         /* bleu royal Lorny */
  --blue2:#3566D6;
  --blue-soft:#7FA0E8;
  --orange:#ED8B1C;       /* accent unique */
  --orange2:#C9710E;
  --orange-soft:#F4B25E;
  --text:#1C2740;         /* corps */
  --muted:#6B7282;        /* gris doux */
  --muted2:#8C8E96;
  --line:#E8E5DC;         /* filets sur crème */
  --line-d:rgba(255,255,255,.14); /* filets sur navy */
  --serif:'Cormorant Garamond',serif;
  --sans:'Manrope',sans-serif;
}
*{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth}
body{font-family:var(--sans);background:var(--paper);color:var(--text);-webkit-font-smoothing:antialiased;line-height:1.7;font-weight:400}
img{max-width:100%;display:block}
a{text-decoration:none;color:inherit}
::selection{background:var(--ink);color:#fff}
.wrap{max-width:1280px;margin:0 auto;padding:0 56px}

/* ---- typographie utilitaire ---- */
.eyebrow{display:inline-flex;align-items:center;gap:11px;font-family:var(--sans);font-weight:600;font-size:11.5px;letter-spacing:.28em;text-transform:uppercase;color:var(--orange2)}
.eyebrow::before{content:"";width:26px;height:1px;background:var(--orange)}
.eyebrow.c{justify-content:center}
.eyebrow.light{color:var(--orange-soft)}
.serif{font-family:var(--serif)}

/* ---- boutons ---- */
.btn{display:inline-flex;align-items:center;justify-content:center;gap:10px;font-family:var(--sans);font-weight:600;font-size:14px;letter-spacing:.02em;padding:15px 26px;border:1px solid transparent;border-radius:3px;cursor:pointer;transition:.25s}
.btn span{transition:transform .25s}
.btn:hover span{transform:translateX(3px)}
.btn-ink{background:var(--ink);color:#fff}.btn-ink:hover{background:var(--orange);color:#fff}
.btn-orange{background:var(--orange);color:#fff;box-shadow:0 12px 26px -10px rgba(237,139,28,.6)}.btn-orange:hover{background:var(--orange2)}
.btn-light{background:#fff;color:var(--ink)}.btn-light:hover{background:var(--orange);color:#fff}
.btn-outline{background:transparent;color:var(--ink);border-color:var(--line)}.btn-outline:hover{border-color:var(--orange);color:var(--orange2)}
.btn-outline.light{color:#fff;border-color:rgba(255,255,255,.35)}.btn-outline.light:hover{border-color:var(--orange);color:var(--orange-soft)}
.linkmore{display:inline-flex;align-items:center;gap:8px;font-size:13.5px;font-weight:600;color:var(--ink);border-bottom:1px solid var(--orange);padding-bottom:5px;transition:.2s}
.linkmore:hover{color:var(--orange2)}

/* ---- header / nav ---- */
header{position:fixed;top:0;left:0;right:0;z-index:60;transition:.35s;background:linear-gradient(180deg,rgba(8,18,34,.45),rgba(8,18,34,0))}
header.solid{background:rgba(255,255,255,.92);backdrop-filter:blur(12px);box-shadow:0 1px 0 var(--line)}
.nav{display:flex;align-items:center;justify-content:space-between;height:84px;transition:.35s}
header.solid .nav{height:72px}
.logo{display:flex;align-items:center;gap:12px}
.logo .chip{height:44px;width:44px;border-radius:11px;background:#fff;box-shadow:0 4px 14px rgba(16,36,63,.16);display:flex;align-items:center;justify-content:center;flex:0 0 auto;transition:.3s}
.logo .chip img{height:34px;width:34px;object-fit:contain}
.logo .bn{font-family:var(--serif);font-weight:600;font-size:21px;letter-spacing:.04em;line-height:1.02;color:#fff;transition:.3s}
.logo .bn small{display:block;font-family:var(--sans);font-weight:500;font-size:9px;letter-spacing:.3em;text-transform:uppercase;color:rgba(255,255,255,.62);margin-top:2px}
header.solid .logo .bn{color:var(--ink)}
header.solid .logo .bn small{color:var(--muted)}
.menu{display:flex;gap:34px;font-size:14px;font-weight:500;color:rgba(255,255,255,.9)}
header.solid .menu{color:var(--text)}
.menu a{position:relative;padding:6px 0;transition:.2s}
.menu a::after{content:"";position:absolute;left:0;bottom:0;width:0;height:1px;background:var(--orange);transition:.25s}
.menu a:hover,.menu a.active{color:var(--orange2)}
.menu a:hover::after,.menu a.active::after{width:100%}
.nav-cta{display:flex;align-items:center;gap:10px}
.nav-login{font-size:14px;font-weight:600;color:#fff;padding:11px 14px;transition:.2s}
header.solid .nav-login{color:var(--ink)}
.nav-login:hover{color:var(--orange2)}
.burger{display:none;flex-direction:column;gap:5px;width:42px;height:42px;align-items:center;justify-content:center;background:transparent;border:none;cursor:pointer}
.burger span{width:22px;height:2px;background:#fff;transition:.3s}
header.solid .burger span{background:var(--ink)}

/* ---- drawer mobile ---- */
.drawer{position:fixed;inset:0;z-index:80;display:none}
.drawer.open{display:block}
.drawer .ov{position:absolute;inset:0;background:rgba(8,18,34,.5);backdrop-filter:blur(2px);animation:fade .25s ease both}
.drawer .panel{position:absolute;top:0;right:0;bottom:0;width:min(82vw,360px);background:var(--ink);padding:30px 28px;display:flex;flex-direction:column;gap:6px;animation:slideX .3s ease both;overflow-y:auto}
.drawer .panel .top{display:flex;align-items:center;justify-content:space-between;margin-bottom:24px}
.drawer .panel .x{background:transparent;border:none;color:#fff;font-size:30px;line-height:1;cursor:pointer}
.drawer .panel a.dl{color:rgba(255,255,255,.88);font-size:16px;font-weight:500;padding:15px 4px;border-bottom:1px solid var(--line-d)}
.drawer .panel a.dl:hover{color:var(--orange-soft)}
.drawer .panel .dcta{margin-top:22px;display:flex;flex-direction:column;gap:10px}
@keyframes fade{from{opacity:0}to{opacity:1}}
@keyframes slideX{from{transform:translateX(100%)}to{transform:translateX(0)}}
@keyframes slideUp{from{opacity:0;transform:translateY(22px)}to{opacity:1;transform:translateY(0)}}
@keyframes heroZoom{from{transform:scale(1.08)}to{transform:scale(1)}}

/* ---- sections ---- */
.section{padding:104px 0}
.section.tight{padding:80px 0}
.dark{background:var(--ink);color:#fff}
.cream{background:var(--paper2)}
.sec-head{margin-bottom:52px}
.sec-head.c{text-align:center}
.sec-head.c .eyebrow{justify-content:center}
.sec-title{font-family:var(--serif);font-weight:500;font-size:clamp(34px,4.6vw,52px);line-height:1.04;letter-spacing:-.01em;color:var(--navy);margin-top:16px}
.dark .sec-title{color:#fff}
.sec-title em{font-style:italic;color:var(--orange2)}
.dark .sec-title em{color:var(--orange-soft)}
.sec-sub{color:var(--muted);font-weight:400;font-size:16.5px;line-height:1.75;max-width:56ch;margin-top:18px}
.dark .sec-sub{color:rgba(255,255,255,.72)}
.head-row{display:flex;align-items:flex-end;justify-content:space-between;gap:30px;margin-bottom:52px;flex-wrap:wrap}

/* ---- page header (pages internes, épuré clair) ---- */
.page-head{padding:150px 0 64px;background:var(--paper2);border-bottom:1px solid var(--line);position:relative;overflow:hidden}
.page-head::after{content:"";position:absolute;top:-30%;right:-8%;width:42%;height:150%;background:radial-gradient(closest-side,rgba(237,139,28,.10),transparent 70%);pointer-events:none}
.page-head .crumb{font-family:var(--sans);font-size:12px;font-weight:600;letter-spacing:.14em;text-transform:uppercase;color:var(--orange2);display:inline-flex;align-items:center;gap:12px;margin-bottom:18px}
.page-head .crumb::before{content:"";width:30px;height:1px;background:var(--orange)}
.page-head h1{font-family:var(--serif);font-weight:600;font-size:clamp(40px,5.6vw,72px);line-height:1.02;letter-spacing:-.015em;color:var(--navy)}
.page-head h1 em{font-style:italic;color:var(--orange2)}
.page-head p{color:var(--muted);font-weight:400;font-size:17px;max-width:58ch;margin-top:18px;line-height:1.75}

/* ---- cartes biens (property) ---- */
.props{display:grid;grid-template-columns:repeat(3,1fr);gap:28px}
.pcard{background:var(--surface);border-radius:5px;overflow:hidden;box-shadow:0 1px 0 rgba(16,36,63,.06);transition:transform .35s,box-shadow .35s;display:flex;flex-direction:column}
.pcard:hover{transform:translateY(-6px);box-shadow:0 30px 56px -26px rgba(16,36,63,.4)}
.pcard .img{position:relative;height:260px;overflow:hidden;background:#e9e5dd}
.pcard .img .bg{position:absolute;inset:0;background:linear-gradient(135deg,#23457e,#0e1d36);background-size:cover;background-position:center;transition:transform .6s ease}
.pcard:hover .img .bg{transform:scale(1.05)}
.pcard .tag{position:absolute;top:15px;left:15px;z-index:2;background:rgba(255,255,255,.95);color:var(--ink);font-size:10.5px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;padding:7px 12px;border-radius:2px}
.pcard .tag.reserve{background:var(--orange);color:#fff}
.pcard .tag.vendu{background:var(--muted);color:#fff}
.pcard .pbody{padding:24px 26px 26px;display:flex;flex-direction:column;flex:1}
.pcard .pty{font-family:var(--sans);font-size:11px;font-weight:600;letter-spacing:.12em;text-transform:uppercase;color:var(--orange2)}
.pcard h3{font-family:var(--serif);font-weight:600;font-size:25px;color:var(--navy);margin:5px 0 16px;line-height:1.1}
.pcard .specs{display:flex;gap:18px;color:var(--muted);font-size:13px;font-weight:500;padding-bottom:16px;border-bottom:1px solid var(--line)}
.pcard .pf{margin-top:16px;display:flex;align-items:baseline;justify-content:space-between;gap:10px}
.pcard .price{font-family:var(--serif);font-weight:600;font-size:26px;color:var(--navy)}
.pcard .price small{display:block;font-family:var(--sans);font-size:10px;font-weight:600;letter-spacing:.08em;text-transform:uppercase;color:var(--muted2);margin-top:2px}
.pcard .apo{font-size:11.5px;font-weight:600;color:var(--orange2);text-align:right;line-height:1.4}

/* ---- accordéon FAQ ---- */
.faqwrap{max-width:860px;margin:0 auto}
.fitem{border-bottom:1px solid var(--line)}
.fq{width:100%;text-align:left;background:none;border:none;cursor:pointer;padding:26px 4px;display:flex;align-items:center;justify-content:space-between;gap:18px;font-family:var(--serif);font-weight:600;font-size:21px;color:var(--navy)}
.fq .ic{flex:0 0 auto;width:30px;height:30px;border-radius:50%;border:1px solid var(--line);display:flex;align-items:center;justify-content:center;color:var(--orange2);transition:.25s;font-size:20px;font-family:var(--sans)}
.fitem.open .fq .ic{transform:rotate(45deg);background:var(--orange);border-color:var(--orange);color:#fff}
.fa{max-height:0;overflow:hidden;transition:max-height .35s ease}
.fa p{padding:0 4px 26px;color:var(--muted);font-weight:400;font-size:15.5px;line-height:1.8;max-width:76ch}

/* ---- CTA image ---- */
.cta-img{position:relative;padding:130px 0;overflow:hidden;text-align:center}
.cta-img .bg{position:absolute;inset:0;background:center/cover no-repeat;}
.cta-img .ov{position:absolute;inset:0;background:linear-gradient(180deg,rgba(11,26,48,.78),rgba(11,26,48,.86))}
.cta-img .inner{position:relative;z-index:3;max-width:640px;margin:0 auto;padding:0 24px}
.cta-img h2{font-family:var(--serif);font-weight:500;font-size:clamp(34px,5vw,54px);line-height:1.08;color:#fff}
.cta-img h2 em{font-style:italic;color:var(--orange-soft)}
.cta-img p{color:rgba(255,255,255,.82);font-weight:400;font-size:16.5px;margin:18px auto 32px;max-width:48ch}

/* ---- footer ---- */
footer{background:var(--ink2);padding:76px 0 36px;color:#fff}
.foot-grid{display:grid;grid-template-columns:2fr 1fr 1fr 1.2fr;gap:48px;padding-bottom:50px;border-bottom:1px solid var(--line-d)}
.foot .logo .bn,.foot .logo .bn small{color:#fff}
.foot p{color:rgba(255,255,255,.6);font-weight:300;font-size:14px;line-height:1.75;max-width:32ch;margin-top:20px}
.foot h5{font-family:var(--sans);font-weight:600;font-size:11px;letter-spacing:.16em;text-transform:uppercase;color:var(--orange-soft);margin-bottom:18px}
.foot ul{list-style:none}.foot li{margin-bottom:11px}
.foot a.fl{color:rgba(255,255,255,.66);font-size:14px;font-weight:300;transition:.2s}.foot a.fl:hover{color:var(--orange-soft)}
.foot-bottom{display:flex;justify-content:space-between;gap:18px;flex-wrap:wrap;margin-top:28px;font-size:12.5px;color:rgba(255,255,255,.45)}
.foot-bottom a{color:var(--orange-soft)}

/* ---- responsive ---- */
@media(max-width:1024px){.wrap{padding:0 32px}.props{grid-template-columns:repeat(2,1fr)}}
@media(max-width:860px){
  .menu,.nav-login{display:none}
  .burger{display:flex}
  .props{grid-template-columns:1fr}
  .foot-grid{grid-template-columns:1fr 1fr;gap:34px}
  .section{padding:74px 0}
  .page-head{padding:120px 0 50px}
}
@media(max-width:520px){.wrap{padding:0 22px}.foot-grid{grid-template-columns:1fr}}
@yield('styles')
</style>
</head>
<body>

<header id="hdr">
  <div class="wrap nav">
    <a class="logo" href="{{ route('home') }}"><span class="chip"><img src="{{ $logo }}" alt="Lorny Conseils Management"></span><span class="bn">Lorny<small>Conseils · Management</small></span></a>
    <nav class="menu">
      <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Accueil</a>
      <a href="{{ route('biens') }}" class="{{ request()->routeIs('biens') ? 'active' : '' }}">Nos offres</a>
      <a href="{{ route('adhesion') }}" class="{{ request()->routeIs('adhesion') ? 'active' : '' }}">L'adhésion</a>
      <a href="{{ route('presentation') }}" class="{{ request()->routeIs('presentation') ? 'active' : '' }}">Le Bureau d'études LCM</a>
      <a href="{{ route('faq') }}" class="{{ request()->routeIs('faq') ? 'active' : '' }}">FAQ</a>
      <a href="{{ route('application') }}" class="{{ request()->routeIs('application') ? 'active' : '' }}">Application</a>
      <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}">Contact</a>
    </nav>
    <div class="nav-cta">
      <a class="nav-login" href="{{ route('consultation.index') }}">Se connecter</a>
      <a class="btn btn-orange" href="{{ route('register.create') }}" style="padding:12px 20px">Créer un compte</a>
      <button class="burger" id="burger" aria-label="Menu"><span></span><span></span><span></span></button>
    </div>
  </div>
</header>

{{-- Drawer mobile --}}
<div class="drawer" id="drawer">
  <div class="ov" data-close></div>
  <div class="panel">
    <div class="top">
      <a class="logo" href="{{ route('home') }}"><span class="chip"><img src="{{ $logo }}" alt=""></span></a>
      <button class="x" data-close aria-label="Fermer">×</button>
    </div>
    <a class="dl" href="{{ route('home') }}">Accueil</a>
    <a class="dl" href="{{ route('biens') }}">Nos offres</a>
    <a class="dl" href="{{ route('adhesion') }}">L'adhésion</a>
    <a class="dl" href="{{ route('presentation') }}">Le Bureau d'études LCM</a>
    <a class="dl" href="{{ route('faq') }}">FAQ</a>
    <a class="dl" href="{{ route('application') }}">Application</a>
    <a class="dl" href="{{ route('contact') }}">Contact</a>
    <div class="dcta">
      <a class="btn btn-orange" href="{{ route('register.create') }}">Créer un compte <span>→</span></a>
      <a class="btn btn-outline light" href="{{ route('consultation.index') }}">Se connecter</a>
    </div>
  </div>
</div>

@yield('content')

<footer>
  <div class="wrap">
    <div class="foot-grid">
      <div class="foot">
        <a class="logo" href="{{ route('home') }}"><span class="chip"><img src="{{ $logo }}" alt=""></span><span class="bn">Lorny<small>Conseils · Management</small></span></a>
        <p>L'expertise immobilière au service de votre patrimoine : conseil, adhésion et paiement échelonné, à Abidjan.</p>
      </div>
      <div class="foot"><h5>Explorer</h5><ul>
        <li><a class="fl" href="{{ route('biens') }}">Nos offres</a></li>
        <li><a class="fl" href="{{ route('adhesion') }}">L'adhésion</a></li>
        <li><a class="fl" href="{{ route('presentation') }}">Le Bureau d'études LCM</a></li>
        <li><a class="fl" href="{{ route('faq') }}">FAQ</a></li>
      </ul></div>
      <div class="foot"><h5>Adhésion</h5><ul>
        <li><a class="fl" href="{{ route('register.create') }}">Créer un compte</a></li>
        <li><a class="fl" href="{{ route('consultation.index') }}">Mon espace</a></li>
        <li><a class="fl" href="{{ route('application') }}">Application mobile</a></li>
        <li><a class="fl" href="{{ route('contact') }}">Contact</a></li>
      </ul></div>
      <div class="foot"><h5>Contact</h5><ul>
        <li><a class="fl" href="{{ route('contact') }}">Cocody, Abidjan — Côte d'Ivoire</a></li>
        <li><a class="fl" href="https://wa.me/2250545870606" target="_blank" rel="noopener">+225 05 45 87 06 06 · WhatsApp</a></li>
        <li><a class="fl" href="mailto:contact@lornyconseils.com">contact@lornyconseils.com</a></li>
      </ul></div>
    </div>
    <div class="foot-bottom">
      <span>© {{ date('Y') }} Lorny Conseils Management — Abidjan. Tous droits réservés.</span>
      <span>Développé par <a href="https://amoniagroup.com" target="_blank" rel="noopener">AMONIA GROUP</a></span>
    </div>
  </div>
</footer>

{{-- Bouton flottant WhatsApp --}}
<a href="https://wa.me/2250545870606?text={{ rawurlencode('Bonjour Lorny Conseils Management, je souhaite des informations.') }}"
   target="_blank" rel="noopener" aria-label="Écrire sur WhatsApp"
   style="position:fixed;right:22px;bottom:22px;z-index:70;display:inline-flex;align-items:center;justify-content:center;width:56px;height:56px;border-radius:50%;background:#25D366;box-shadow:0 12px 30px rgba(37,211,102,.42);transition:transform .2s"
   onmouseover="this.style.transform='scale(1.08)'" onmouseout="this.style.transform='scale(1)'">
  <svg viewBox="0 0 32 32" width="28" height="28" fill="#fff" aria-hidden="true"><path d="M16.04 3C9.4 3 4 8.4 4 15.04c0 2.12.55 4.18 1.6 6L4 29l8.2-2.15a12 12 0 0 0 3.84.63h.01C22.7 27.48 28 22.08 28 15.44 28 8.8 22.68 3 16.04 3zm0 21.9h-.01c-1.18 0-2.34-.32-3.35-.92l-.24-.14-4.87 1.28 1.3-4.75-.16-.25a9.94 9.94 0 0 1-1.52-5.27c0-5.5 4.48-9.98 9.99-9.98 2.67 0 5.18 1.04 7.06 2.93a9.92 9.92 0 0 1 2.92 7.06c0 5.5-4.48 9.98-9.99 9.98zm5.48-7.48c-.3-.15-1.78-.88-2.06-.98-.28-.1-.48-.15-.68.15-.2.3-.78.98-.95 1.18-.18.2-.35.22-.65.07-.3-.15-1.27-.47-2.42-1.49-.9-.8-1.5-1.78-1.67-2.08-.18-.3-.02-.46.13-.61.13-.13.3-.35.45-.52.15-.18.2-.3.3-.5.1-.2.05-.38-.02-.53-.08-.15-.68-1.63-.93-2.23-.24-.59-.49-.5-.68-.51l-.58-.01c-.2 0-.53.07-.8.38-.28.3-1.05 1.03-1.05 2.5 0 1.48 1.08 2.9 1.23 3.1.15.2 2.12 3.24 5.13 4.54.72.31 1.27.5 1.71.64.72.23 1.37.2 1.89.12.58-.09 1.78-.73 2.03-1.43.25-.7.25-1.3.18-1.43-.07-.13-.27-.2-.57-.35z"/></svg>
</a>

<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
<script>
const REDUCED=window.matchMedia('(prefers-reduced-motion: reduce)').matches;
// header solidify — transparent seulement sur la home (hero sombre), solide ailleurs
const hdr=document.getElementById('hdr');
const hasHero=!!document.getElementById('hero');
const onScroll=()=>hdr.classList.toggle('solid', !hasHero || window.scrollY>40);
window.addEventListener('scroll',onScroll,{passive:true});onScroll();
// drawer mobile
const drawer=document.getElementById('drawer');
document.getElementById('burger').onclick=()=>{drawer.classList.add('open');document.body.style.overflow='hidden';};
drawer.querySelectorAll('[data-close],.dl').forEach(el=>el.addEventListener('click',()=>{drawer.classList.remove('open');document.body.style.overflow='';}));
document.addEventListener('keydown',e=>{if(e.key==='Escape'){drawer.classList.remove('open');document.body.style.overflow='';}});
// accordéon
document.querySelectorAll('.fitem').forEach(it=>{const q=it.querySelector('.fq'),a=it.querySelector('.fa');q.onclick=()=>{const open=it.classList.contains('open');document.querySelectorAll('.fitem.open').forEach(o=>{o.classList.remove('open');o.querySelector('.fa').style.maxHeight='0'});if(!open){it.classList.add('open');a.style.maxHeight=a.scrollHeight+'px';}};});
// reveals
function reveals(){
  if(REDUCED)return;
  gsap.registerPlugin(ScrollTrigger);
  if(document.getElementById('heroBg')) gsap.to('#heroBg',{yPercent:10,ease:'none',scrollTrigger:{trigger:'#hero',start:'top top',end:'bottom top',scrub:1}});
  const sel='[data-reveal]';
  gsap.utils.toArray(sel).forEach(el=>gsap.from(el,{opacity:0,y:30,duration:.8,ease:'power3.out',scrollTrigger:{trigger:el,start:'top 92%',once:true}}));
  gsap.utils.toArray('[data-count]').forEach(el=>{const num=parseFloat(el.getAttribute('data-count'))||0,o={v:0},suf=el.getAttribute('data-suffix')||'';gsap.to(o,{v:num,duration:1.6,ease:'power1.out',scrollTrigger:{trigger:el,start:'top 96%',once:true},onUpdate:()=>el.textContent=Math.round(o.v).toLocaleString('fr-FR')+suf});});
  gsap.delayedCall(2.4,()=>document.querySelectorAll(sel).forEach(el=>{if(parseFloat(getComputedStyle(el).opacity)<0.05)gsap.to(el,{opacity:1,y:0,duration:.4});}));
}
if(document.readyState!=='loading')reveals();else document.addEventListener('DOMContentLoaded',reveals);
</script>
@stack('scripts')
</body>
</html>
