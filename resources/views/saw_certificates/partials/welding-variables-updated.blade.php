<!-- Welding Variables Table Header - FCAW Specific -->
<table class="content-table">
    <tr>
        <td colspan="5" class="section-header">TESTING VARIABLES AND QUALIFICATION LIMITS</td>
    </tr>
</table>

<!-- Variables Table -->
<table class="variables-table">
    <tr>
        <td class="var-label">Welding Variables (QW-350)</td>
        <td class="var-value" style="width: 150px;"><strong>Actual Values</strong></td>
        <td class="var-range" style="width: 200px;"><strong>Range Qualified</strong></td>
    </tr>
    <tr>
        <td class="var-label">Welding process(es):</td>
        <td class="var-value">FCAW</td>
        <td class="var-range">FCAW</td>
    </tr>
    <tr>
        <td class="var-label">Type (i.e., manual, semi-automatic) used:</td>
        <td class="var-value">Semi-automatic</td>
        <td class="var-range">Semi-automatic</td>
    </tr>
    <tr>
        <td class="var-label">Backing (with/without):</td>
        <td class="var-value">{{ $certificate->backing_manual ?? $certificate->backing }}</td>
        <td class="var-range">{{ $certificate->backing_range }}</td>
    </tr>
</table>
