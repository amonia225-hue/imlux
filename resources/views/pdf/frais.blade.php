<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    @include('pdf._styles')
</head>
<body>
@php
    $docTitle = 'REÇU';
    $docNumber = 'FO-' . str_pad($souscripteur->id, 5, '0', STR_PAD_LEFT);
    $docDate = optional($souscripteur->frais_ouverture_date)->format('d/m/Y') ?? now()->format('d/m/Y');
@endphp

@include('pdf._header')
@include('pdf._footer')

<div class="content">

    <table width="100%" cellspacing="0" style="margin-bottom:4px;">
        <tr>
            <td><span class="sec" style="margin-top:0;">Reçu — Frais d'ouverture de dossier</span></td>
            <td style="text-align:right;"><span class="pill pill-green">Réglé</span></td>
        </tr>
    </table>

    <div class="card">
        <div class="card-h">Adhérent</div>
        <div class="card-b">
            <table width="100%">
                <tr>
                    <td width="50%"><span class="label">Nom complet</span><br><span class="value">{{ $souscripteur->fullName() }}</span></td>
                    <td width="50%"><span class="label">Identifiant</span><br><span class="value">{{ $souscripteur->uid }}</span></td>
                </tr>
                <tr>
                    <td style="padding-top:9px;"><span class="label">Téléphone</span><br>{{ $souscripteur->phone ?? '—' }}</td>
                    <td style="padding-top:9px;"><span class="label">Email</span><br>{{ $souscripteur->email ?? '—' }}</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="sec">Détail</div>
    <table class="items">
        <thead>
            <tr><th>Désignation</th><th class="r">Montant</th></tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    Frais d'ouverture de dossier
                    <div class="desc-sub">Réglés le {{ optional($souscripteur->frais_ouverture_date)->format('d/m/Y') ?? now()->format('d/m/Y') }}</div>
                </td>
                <td class="r"><b>{{ number_format((float) $souscripteur->frais_ouverture, 0, ',', ' ') }} FCFA</b></td>
            </tr>
        </tbody>
    </table>

    <table class="total-box" width="100%" cellspacing="0" style="margin-top:18px;">
        <tr>
            <td>
                <div class="t-label">Total réglé</div>
                <div class="t-amount">{{ number_format((float) $souscripteur->frais_ouverture, 0, ',', ' ') }} FCFA</div>
            </td>
            <td style="text-align:right;"><span class="pill pill-green">Acquitté</span></td>
        </tr>
    </table>

    <div class="note">
        Ces frais d'ouverture de dossier sont <b>distincts du prix d'achat du bien</b> et de l'échéancier de paiement.
        Le présent document atteste de leur règlement.
    </div>

    <div class="thanks">Merci de votre confiance.</div>

    <div class="stamp">
        <div class="stamp-line">Cachet &amp; signature du bureau d'études</div>
    </div>

</div>
</body>
</html>
