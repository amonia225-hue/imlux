<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    @include('pdf._styles')
</head>
<body>
@php
    $docTitle = 'ATTESTATION';
    $docNumber = 'ATT-' . $souscripteur->uid;
    $docDate = now()->format('d/m/Y');
    $S = fn($k) => \App\Models\Setting::get($k);
@endphp

@include('pdf._header')
@include('pdf._footer')

<div class="content">

    <table width="100%" cellspacing="0" style="margin-bottom:6px;">
        <tr>
            <td><span class="sec" style="margin-top:0;">
                @if($isSolde) Attestation de paiement complet @else Attestation de paiement partiel @endif
            </span></td>
            <td style="text-align:right;">
                @if($isSolde)<span class="pill pill-green">Soldé</span>@else<span class="pill pill-orange">En cours</span>@endif
            </td>
        </tr>
    </table>

    <div class="note" style="border-left-color:#1E40AF;">
        Nous soussignés, <b>{{ $S('company_name') }}</b>, attestons que
        <b>{{ $souscripteur->fullName() }}</b> (Identifiant <b>{{ $souscripteur->uid }}</b>)
        @if($isSolde)
            a intégralement réglé la somme de <b>{{ number_format((float) $souscription->total_price, 0, ',', ' ') }} FCFA</b>
            au titre de son adhésion au programme <b>{{ $programme->name }}</b>, lot <b>{{ $lot->reference }}</b>.
        @else
            a versé <b>{{ number_format($totalVerse, 0, ',', ' ') }} FCFA</b>
            sur un total de <b>{{ number_format((float) $souscription->total_price, 0, ',', ' ') }} FCFA</b>
            au titre de son adhésion au programme <b>{{ $programme->name }}</b>, lot <b>{{ $lot->reference }}</b>.
            Solde restant : <b>{{ number_format($resteAPayer, 0, ',', ' ') }} FCFA</b>.
        @endif
    </div>

    <table class="cards" width="100%" cellspacing="0" style="margin-top:18px;">
        <tr>
            <td width="49%">
                <div class="card">
                    <div class="card-h">Adhérent</div>
                    <div class="card-b">
                        <p><span class="value">{{ $souscripteur->fullName() }}</span></p>
                        <p><span class="label">Identifiant</span> &nbsp; {{ $souscripteur->uid }}</p>
                        <p><span class="label">Téléphone</span> &nbsp; {{ $souscripteur->phone ?? '—' }}</p>
                        <p><span class="label">Email</span> &nbsp; {{ $souscripteur->email ?? '—' }}</p>
                        @if($souscripteur->id_type)<p><span class="label">Pièce</span> &nbsp; {{ $souscripteur->id_type }} — {{ $souscripteur->id_number }}</p>@endif
                    </div>
                </div>
            </td>
            <td width="2%">&nbsp;</td>
            <td width="49%">
                <div class="card">
                    <div class="card-h">Adhésion</div>
                    <div class="card-b">
                        <p><span class="label">Programme</span><br><span class="value">{{ $programme->name }}</span></p>
                        <p><span class="label">Localisation</span> &nbsp; {{ $programme->location }}</p>
                        <p><span class="label">Lot</span> &nbsp; {{ $lot->reference }} — {{ $lot->type_logement }}</p>
                        <p><span class="label">Date d'adhésion</span> &nbsp; {{ \Carbon\Carbon::parse($souscription->date_souscription)->format('d/m/Y') }}</p>
                    </div>
                </div>
            </td>
        </tr>
    </table>

    <div class="sec">Situation du compte</div>
    <table class="summary">
        <tr><td class="s-lab">Prix total du bien</td><td class="s-val">{{ number_format((float) $souscription->total_price, 0, ',', ' ') }} FCFA</td></tr>
        <tr><td class="s-lab">Total versé</td><td class="s-val paid">{{ number_format($totalVerse, 0, ',', ' ') }} FCFA</td></tr>
        <tr><td class="s-lab">Reste à payer</td><td class="s-val {{ $resteAPayer > 0 ? 'due' : 'paid' }}">{{ number_format($resteAPayer, 0, ',', ' ') }} FCFA</td></tr>
        <tr class="grand"><td class="s-lab">Progression</td><td class="s-val" style="color:#1E40AF;">{{ number_format($souscription->progressPercent(), 1) }} %</td></tr>
    </table>

    <div class="sec">Historique des versements</div>
    <table class="items">
        <thead>
            <tr><th>#</th><th>Date</th><th>Mode · Réf.</th><th class="r">Montant</th></tr>
        </thead>
        <tbody>
            @foreach($versements as $i => $v)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($v->payment_date)->format('d/m/Y') }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $v->payment_method)) }}@if($v->reference) · {{ $v->reference }}@endif</td>
                    <td class="r"><b>{{ number_format((float) $v->amount, 0, ',', ' ') }} FCFA</b></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table width="100%" style="margin-top:38px;">
        <tr>
            <td width="50%"><div class="stamp-line" style="float:none; width:200px;">L'adhérent</div></td>
            <td width="50%"><div class="stamp-line">Cachet &amp; signature du bureau d'études</div></td>
        </tr>
    </table>

    <p style="margin-top:18px; font-size:10px; color:#8a93a8; text-align:center;">Cette attestation est délivrée pour servir et valoir ce que de droit.</p>

</div>
</body>
</html>
