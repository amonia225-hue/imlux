@extends('public.layout')
@section('title', 'Dépôt reçu')
@section('content')
<div style="max-width:680px;margin:9rem auto 4rem;padding:0 1rem;text-align:center">
    <h1 style="font-family:'Cormorant Garamond',serif;font-size:2rem;color:var(--ink);margin-bottom:.8rem">Merci, votre dépôt nous est parvenu</h1>
    <p style="color:var(--muted);line-height:1.7">
        Transmis le {{ $soumission->soumise_le?->format('d/m/Y à H:i') }} —
        {{ $soumission->biens()->count() }} bien(s) déclaré(s).
    </p>
    <p style="color:var(--muted);line-height:1.7;margin-top:1rem">
        Notre bureau d'études examine votre proposition et reviendra vers vous.
        Ce formulaire n'est plus modifiable ; pour un ajout, demandez-nous un nouveau lien.
    </p>
    @if ($soumission->statut === 'rejetee' && $soumission->note_admin)
        <div style="margin-top:1.6rem;background:#FDECEA;border:1px solid #F3C9C2;color:#8C2A1C;border-radius:12px;padding:1rem;text-align:left">
            <strong>Retour du bureau d'études</strong><br>{{ $soumission->note_admin }}
        </div>
    @endif
</div>
@endsection
