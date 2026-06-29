<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    @include('pdf._styles')
</head>
<body>
@php
    $docTitle = 'FACTURE';
    $docNumber = str_pad($versement->id, 6, '0', STR_PAD_LEFT);
    $docDate = \Carbon\Carbon::parse($versement->payment_date)->format('d/m/Y');
@endphp

@include('pdf._header')
@include('pdf._footer')

<div class="content">

    <table class="cards" width="100%" cellspacing="0">
        <tr>
            <td width="49%">
                <div class="card">
                    <div class="card-h">Facturé à l'adhérent</div>
                    <div class="card-b">
                        <p><span class="value">{{ $souscripteur->fullName() }}</span></p>
                        <p><span class="label">Identifiant</span><br>{{ $souscripteur->uid }}</p>
                        <p><span class="label">Téléphone</span> &nbsp; {{ $souscripteur->phone ?? '—' }}
                           &nbsp;&nbsp;<span class="label">Email</span> &nbsp; {{ $souscripteur->email ?? '—' }}</p>
                    </div>
                </div>
            </td>
            <td width="2%">&nbsp;</td>
            <td width="49%">
                <div class="card">
                    <div class="card-h">Détails de l'adhésion</div>
                    <div class="card-b">
                        <p><span class="label">Programme</span><br><span class="value">{{ $programme->name }}</span></p>
                        <p><span class="label">Lot</span> &nbsp; {{ $lot->reference }}
                           &nbsp;&nbsp;<span class="label">Type</span> &nbsp; {{ $lot->type_logement }}</p>
                        <p><span class="label">Prix total</span> &nbsp; <b>{{ number_format((float) $souscription->total_price, 0, ',', ' ') }} FCFA</b></p>
                    </div>
                </div>
            </td>
        </tr>
    </table>

    <div class="sec">Objet du règlement</div>
    <table class="items">
        <thead>
            <tr><th>Désignation</th><th class="r">Montant</th></tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    Versement sur adhésion — {{ $programme->name }}
                    <div class="desc-sub">Lot {{ $lot->reference }} · Mode : {{ ucfirst(str_replace('_', ' ', $versement->payment_method)) }}@if($versement->reference) · Réf {{ $versement->reference }}@endif</div>
                </td>
                <td class="r"><b>{{ number_format((float) $versement->amount, 0, ',', ' ') }} FCFA</b></td>
            </tr>
        </tbody>
    </table>

    <table width="100%" cellspacing="0" style="margin-top:18px;">
        <tr>
            <td width="52%" style="vertical-align:top; padding-right:18px;">
                <div class="sec" style="margin-top:0;">Récapitulatif de l'adhésion</div>
                <table class="summary">
                    <tr><td class="s-lab">Prix total du bien</td><td class="s-val">{{ number_format((float) $souscription->total_price, 0, ',', ' ') }} FCFA</td></tr>
                    <tr><td class="s-lab">Total versé à ce jour</td><td class="s-val paid">{{ number_format($totalVerse, 0, ',', ' ') }} FCFA</td></tr>
                    <tr class="grand"><td class="s-lab">Reste à payer</td><td class="s-val {{ $resteAPayer > 0 ? 'due' : 'paid' }}">{{ number_format($resteAPayer, 0, ',', ' ') }} FCFA</td></tr>
                </table>
            </td>
            <td width="48%" style="vertical-align:top;">
                <table class="total-box" width="100%" cellspacing="0">
                    <tr>
                        <td>
                            <div class="t-label">Montant réglé</div>
                            <div class="t-amount">{{ number_format((float) $versement->amount, 0, ',', ' ') }} FCFA</div>
                            <div class="t-mode">{{ \Carbon\Carbon::parse($versement->payment_date)->format('d/m/Y') }} · {{ ucfirst(str_replace('_', ' ', $versement->payment_method)) }}</div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    @if($versement->note)
        <div class="note"><b>Note :</b> {{ $versement->note }}</div>
    @endif

    <div class="thanks">Merci de votre confiance.</div>

    <div class="stamp">
        <div class="stamp-line">Cachet &amp; signature du bureau d'études</div>
    </div>

</div>
</body>
</html>
