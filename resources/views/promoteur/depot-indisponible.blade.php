@extends('public.layout')
@section('title', 'Lien indisponible')
@section('content')
<div style="max-width:640px;margin:9rem auto 4rem;padding:0 1rem;text-align:center">
    <h1 style="font-family:'Cormorant Garamond',serif;font-size:2rem;color:var(--ink);margin-bottom:.8rem">Lien indisponible</h1>
    <p style="color:var(--muted);line-height:1.7">{{ $motif }}</p>
    <p style="color:var(--muted);line-height:1.7;margin-top:1rem">
        Contactez le bureau d'études pour qu'un nouveau lien vous soit transmis.
    </p>
</div>
@endsection
