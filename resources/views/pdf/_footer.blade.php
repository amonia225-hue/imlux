@php
    $S = fn($k) => \App\Models\Setting::get($k);
    $line1 = $S('company_name');
    if ($S('company_address')) { $line1 .= ' — ' . $S('company_address'); }
    $bits = [];
    if ($S('company_phone')) { $bits[] = 'Tél ' . $S('company_phone'); }
    if ($S('company_email')) { $bits[] = $S('company_email'); }
    if ($S('company_rccm'))  { $bits[] = 'RCCM ' . $S('company_rccm'); }
    if ($S('company_ncc'))   { $bits[] = 'NCC ' . $S('company_ncc'); }
    $line2 = implode(' · ', $bits);
@endphp
<div class="footer">
    <table width="100%">
        <tr>
            <td>
                <span class="ft-strong">{{ $line1 }}</span><br>
                {{ $line2 }}
            </td>
            <td style="text-align:right;">
                <span class="ft-accent">Lorny Conseils Management</span><br>
                Émis le {{ now()->format('d/m/Y à H:i') }}
            </td>
        </tr>
    </table>
</div>
