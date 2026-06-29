@php $S = fn($k) => \App\Models\Setting::get($k); @endphp
<div class="band">
    <table width="100%">
        <tr>
            @if(!empty($logoSrc))
                <td class="logo-cell"><img src="{{ $logoSrc }}" alt="Logo"></td>
                <td style="padding-left:14px;">
            @else
                <td>
            @endif
                <div class="brand-name">{{ $S('company_name') }}</div>
                @if($S('company_tagline'))<div class="brand-tag">{{ $S('company_tagline') }}</div>@endif
            </td>
            <td style="text-align:right;">
                <div class="doc-type">{{ $docTitle }}</div>
                <div class="doc-meta">N° <b>{{ $docNumber }}</b> &nbsp;·&nbsp; {{ $docDate }}</div>
            </td>
        </tr>
    </table>
</div>
<div class="accent-bar"></div>
