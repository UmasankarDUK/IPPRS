@extends('layouts.app')

@section('content')
<div class="min-h-screen" style="background:#F0F7F4;">
<div class="max-w-full px-4 sm:px-6 lg:px-8 py-8">

    {{-- Header --}}
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('ebola.index') }}" class="text-xs font-semibold hover:underline" style="color:#006B4F;">← Ebola Dashboard</a>
            </div>
            <h1 class="text-xl font-black" style="color:#111827;">Daily Institution Report Entry</h1>
            <p class="text-sm mt-0.5" style="color:#6B7280;">Enter aggregate figures for each MCH — matches the daily bulletin format.</p>
        </div>
        <div class="flex items-center gap-3">
            {{-- Date selector --}}
            <form method="GET" action="{{ route('ebola.report.create') }}" class="flex items-center gap-2">
                <label class="text-xs font-bold" style="color:#374151;">Reporting Date:</label>
                <input type="date" name="report_date" value="{{ $date }}" onchange="this.form.submit()"
                       class="px-3 py-2 rounded-lg text-sm font-semibold border focus:outline-none"
                       style="border-color:#A7D4C3;color:#111827;">
            </form>
        </div>
    </div>

    {{-- Validation errors --}}
    @if($errors->any())
        <div class="mb-4 px-4 py-3 rounded-xl border text-sm" style="background:#FEF2F2;border-color:#FECACA;color:#DC2626;">
            <ul class="list-disc list-inside space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Main Form --}}
    <form method="POST" action="{{ route('ebola.report.store') }}" id="reportForm">
        @csrf
        <input type="hidden" name="report_date" value="{{ $date }}">

        <div class="bg-white rounded-2xl overflow-hidden" style="border:1px solid #E2EDE9;box-shadow:0 2px 12px rgba(0,107,79,0.07);">

            {{-- Card header --}}
            <div class="px-6 py-4 flex items-center justify-between" style="background:linear-gradient(135deg,#F0FAF6,#E8F5F0);border-bottom:1px solid #D1EDE4;">
                <div>
                    <h2 class="text-sm font-black" style="color:#006B4F;">Format for reporting of probable/confirmed Ebola cases from institutions</h2>
                    <p class="text-xs mt-0.5 font-mono" style="color:#6B7280;">Date: <span class="font-bold" style="color:#111827;">{{ \Carbon\Carbon::parse($date)->format('d M Y') }}</span></p>
                </div>
                <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold text-white transition hover:opacity-90"
                        style="background:linear-gradient(135deg,#006B4F,#00875F);box-shadow:0 4px 12px rgba(0,107,79,0.3);">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    Save Report
                </button>
            </div>

            {{-- Scrollable table --}}
            <div class="overflow-x-auto w-full">
                <table class="w-full border-collapse text-xs" id="bulletinTable">
                    <thead>
                        {{-- Group row 1 --}}
                        <tr style="background:#F0FAF6;">
                            <th rowspan="2" class="sticky-col px-3 py-3 text-left text-[9px] font-black uppercase tracking-wider font-mono border-r-2 whitespace-nowrap" style="color:#006B4F;border-color:#D1EDE4;background:#E8F5F0;min-width:180px;">MCH / Hospital</th>
                            <th rowspan="2" class="px-3 py-3 text-center text-[9px] font-black uppercase tracking-wider font-mono border-r-2 whitespace-nowrap" style="color:#374151;border-color:#D1EDE4;background:#F9FAFB;min-width:80px;">Cases<br>Reported</th>

                            {{-- Admissions group --}}
                            <th colspan="7" class="px-3 py-2 text-center text-[9px] font-black uppercase tracking-wider font-mono border-r-2" style="color:#D97706;background:#FFFBEB;border-color:#FDE68A;">Daily Report — Total Admissions of Probable Cases</th>

                            {{-- Deaths --}}
                            <th rowspan="2" class="px-3 py-3 text-center text-[9px] font-black uppercase tracking-wider font-mono border-r-2 whitespace-nowrap" style="color:#DC2626;background:#FFF1F2;border-color:#FECACA;min-width:70px;">Deaths<br>(Probable)</th>

                            {{-- Testing group --}}
                            <th colspan="2" class="px-3 py-2 text-center text-[9px] font-black uppercase tracking-wider font-mono border-r-2" style="color:#2563EB;background:#EFF6FF;border-color:#BFDBFE;">Daily Testing</th>

                            {{-- Positives group --}}
                            <th colspan="7" class="px-3 py-2 text-center text-[9px] font-black uppercase tracking-wider font-mono" style="color:#DC2626;background:#FFF1F2;border-color:#FECACA;">Daily Report — Confirmed Positive Cases by Tier</th>
                        </tr>

                        {{-- Group row 2 — sub-headers --}}
                        <tr style="background:#F9FAFB;border-bottom:2px solid #D1EDE4;">
                            {{-- Admissions sub-cols --}}
                            <th class="px-2 py-2 text-center text-[8px] font-black font-mono whitespace-nowrap" style="color:#6B7280;border-right:1px solid #E2EDE9;background:#FFFBEB;">Home<br>Quarantine</th>
                            <th class="px-2 py-2 text-center text-[8px] font-black font-mono whitespace-nowrap" style="color:#6B7280;border-right:1px solid #E2EDE9;background:#FFFBEB;">Inst.<br>Quarantine</th>
                            <th class="px-2 py-2 text-center text-[8px] font-black font-mono whitespace-nowrap" style="color:#6B7280;border-right:1px solid #E2EDE9;background:#FFFBEB;">Iso<br>(no O₂)</th>
                            <th class="px-2 py-2 text-center text-[8px] font-black font-mono whitespace-nowrap" style="color:#6B7280;border-right:1px solid #E2EDE9;background:#FFFBEB;">Iso<br>(O₂)</th>
                            <th class="px-2 py-2 text-center text-[8px] font-black font-mono whitespace-nowrap" style="color:#6B7280;border-right:1px solid #E2EDE9;background:#FFFBEB;">ICU<br>(no O₂)</th>
                            <th class="px-2 py-2 text-center text-[8px] font-black font-mono whitespace-nowrap" style="color:#6B7280;border-right:1px solid #E2EDE9;background:#FFFBEB;">ICU<br>(O₂)</th>
                            <th class="px-2 py-2 text-center text-[8px] font-black font-mono whitespace-nowrap" style="color:#D97706;border-right:2px solid #FDE68A;background:#FFFBEB;font-weight:900;">ICU<br>(Vent)</th>

                            {{-- Testing sub-cols --}}
                            <th class="px-2 py-2 text-center text-[8px] font-black font-mono whitespace-nowrap" style="color:#6B7280;border-right:1px solid #E2EDE9;background:#EFF6FF;">Tests<br>Sent</th>
                            <th class="px-2 py-2 text-center text-[8px] font-black font-mono whitespace-nowrap" style="color:#6B7280;border-right:2px solid #BFDBFE;background:#EFF6FF;">Lab<br>Confirmed</th>

                            {{-- Positives sub-cols --}}
                            <th class="px-2 py-2 text-center text-[8px] font-black font-mono whitespace-nowrap" style="color:#6B7280;border-right:1px solid #E2EDE9;background:#FFF1F2;">+ve Home<br>Quar.</th>
                            <th class="px-2 py-2 text-center text-[8px] font-black font-mono whitespace-nowrap" style="color:#6B7280;border-right:1px solid #E2EDE9;background:#FFF1F2;">+ve Inst.<br>Quar.</th>
                            <th class="px-2 py-2 text-center text-[8px] font-black font-mono whitespace-nowrap" style="color:#6B7280;border-right:1px solid #E2EDE9;background:#FFF1F2;">+ve<br>Isolation</th>
                            <th class="px-2 py-2 text-center text-[8px] font-black font-mono whitespace-nowrap" style="color:#6B7280;border-right:1px solid #E2EDE9;background:#FFF1F2;">+ve ICU<br>(no O₂)</th>
                            <th class="px-2 py-2 text-center text-[8px] font-black font-mono whitespace-nowrap" style="color:#6B7280;border-right:1px solid #E2EDE9;background:#FFF1F2;">+ve ICU<br>(O₂)</th>
                            <th class="px-2 py-2 text-center text-[8px] font-black font-mono whitespace-nowrap" style="color:#6B7280;border-right:1px solid #E2EDE9;background:#FFF1F2;">+ve ICU<br>(Vent)</th>
                            <th class="px-2 py-2 text-center text-[8px] font-black font-mono whitespace-nowrap" style="color:#DC2626;background:#FFF1F2;font-weight:900;">Total<br>Positives</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($institutions as $i => $institution)
                            @php $ex = $existing->get($institution->id); @endphp
                            <tr class="report-row" style="border-bottom:1px solid #F3F4F6;" onmouseenter="this.style.background='#F0FAF6'" onmouseleave="this.style.background=''">
                                {{-- Hidden institution ID --}}
                                <input type="hidden" name="rows[{{ $i }}][health_institution_id]" value="{{ $institution->id }}">

                                {{-- MCH Name --}}
                                <td class="px-3 py-2 font-bold whitespace-nowrap" style="color:#111827;border-right:2px solid #D1EDE4;background:#FAFFFE;">
                                    <div class="flex items-center gap-2">
                                        <span class="text-[9px] font-black font-mono w-5 text-center rounded" style="color:#9CA3AF;">{{ $i + 1 }}</span>
                                        {{ $institution->name }}
                                    </div>
                                </td>

                                {{-- Cases Reported --}}
                                <td class="px-1 py-1.5" style="border-right:2px solid #D1EDE4;background:#FAFFFE;">
                                    @include('ebola.report._cell', ['name' => "rows[{$i}][total_cases_reported]", 'val' => $ex->total_cases_reported ?? 0, 'color' => '#006B4F'])
                                </td>

                                {{-- Admissions --}}
                                <td class="px-1 py-1.5" style="border-right:1px solid #E2EDE9;">@include('ebola.report._cell', ['name' => "rows[{$i}][home_quarantine]", 'val' => $ex->home_quarantine ?? 0])</td>
                                <td class="px-1 py-1.5" style="border-right:1px solid #E2EDE9;">@include('ebola.report._cell', ['name' => "rows[{$i}][inst_quarantine]", 'val' => $ex->inst_quarantine ?? 0])</td>
                                <td class="px-1 py-1.5" style="border-right:1px solid #E2EDE9;background:#FFFDF5;">@include('ebola.report._cell', ['name' => "rows[{$i}][isolation_no_o2]", 'val' => $ex->isolation_no_o2 ?? 0])</td>
                                <td class="px-1 py-1.5" style="border-right:1px solid #E2EDE9;background:#FFFDF5;">@include('ebola.report._cell', ['name' => "rows[{$i}][isolation_with_o2]", 'val' => $ex->isolation_with_o2 ?? 0])</td>
                                <td class="px-1 py-1.5" style="border-right:1px solid #E2EDE9;background:#FFFDF5;">@include('ebola.report._cell', ['name' => "rows[{$i}][icu_no_o2]", 'val' => $ex->icu_no_o2 ?? 0])</td>
                                <td class="px-1 py-1.5" style="border-right:1px solid #E2EDE9;background:#FFFDF5;">@include('ebola.report._cell', ['name' => "rows[{$i}][icu_with_o2]", 'val' => $ex->icu_with_o2 ?? 0])</td>
                                <td class="px-1 py-1.5" style="border-right:2px solid #FDE68A;background:#FFFDF5;">@include('ebola.report._cell', ['name' => "rows[{$i}][icu_ventilator]", 'val' => $ex->icu_ventilator ?? 0, 'color' => '#D97706'])</td>

                                {{-- Deaths --}}
                                <td class="px-1 py-1.5" style="border-right:2px solid #FECACA;background:#FFF8F8;">@include('ebola.report._cell', ['name' => "rows[{$i}][deaths_probable]", 'val' => $ex->deaths_probable ?? 0, 'color' => '#DC2626'])</td>

                                {{-- Testing --}}
                                <td class="px-1 py-1.5" style="border-right:1px solid #E2EDE9;background:#F5F9FF;">@include('ebola.report._cell', ['name' => "rows[{$i}][tests_sent]", 'val' => $ex->tests_sent ?? 0])</td>
                                <td class="px-1 py-1.5" style="border-right:2px solid #BFDBFE;background:#F5F9FF;">@include('ebola.report._cell', ['name' => "rows[{$i}][lab_confirmed]", 'val' => $ex->lab_confirmed ?? 0, 'color' => '#2563EB'])</td>

                                {{-- Positives --}}
                                <td class="px-1 py-1.5" style="border-right:1px solid #E2EDE9;background:#FFF8F8;">@include('ebola.report._cell', ['name' => "rows[{$i}][positives_home]", 'val' => $ex->positives_home ?? 0, 'color' => '#DC2626'])</td>
                                <td class="px-1 py-1.5" style="border-right:1px solid #E2EDE9;background:#FFF8F8;">@include('ebola.report._cell', ['name' => "rows[{$i}][positives_inst]", 'val' => $ex->positives_inst ?? 0, 'color' => '#DC2626'])</td>
                                <td class="px-1 py-1.5" style="border-right:1px solid #E2EDE9;background:#FFF8F8;">@include('ebola.report._cell', ['name' => "rows[{$i}][positives_isolation]", 'val' => $ex->positives_isolation ?? 0, 'color' => '#DC2626'])</td>
                                <td class="px-1 py-1.5" style="border-right:1px solid #E2EDE9;background:#FFF8F8;">@include('ebola.report._cell', ['name' => "rows[{$i}][positives_icu_no_o2]", 'val' => $ex->positives_icu_no_o2 ?? 0, 'color' => '#DC2626'])</td>
                                <td class="px-1 py-1.5" style="border-right:1px solid #E2EDE9;background:#FFF8F8;">@include('ebola.report._cell', ['name' => "rows[{$i}][positives_icu_with_o2]", 'val' => $ex->positives_icu_with_o2 ?? 0, 'color' => '#DC2626'])</td>
                                <td class="px-1 py-1.5" style="border-right:1px solid #E2EDE9;background:#FFF8F8;">@include('ebola.report._cell', ['name' => "rows[{$i}][positives_icu_ventilator]", 'val' => $ex->positives_icu_ventilator ?? 0, 'color' => '#DC2626'])</td>

                                {{-- Auto-calculated total positives (read only) --}}
                                <td class="px-2 py-1.5 text-center font-black font-mono total-pos" data-row="{{ $i }}" style="color:#DC2626;background:#FFF1F2;min-width:55px;">
                                    {{ $ex ? $ex->total_positives : 0 }}
                                </td>
                            </tr>
                        @endforeach

                        {{-- Column totals row --}}
                        <tr id="totalsRow" style="background:#E8F5F0;border-top:2px solid #A7D4C3;">
                            <td class="px-3 py-2.5 font-black text-xs" style="color:#006B4F;border-right:2px solid #D1EDE4;">DAILY TOTALS</td>
                            @foreach(['total_cases_reported','home_quarantine','inst_quarantine','isolation_no_o2','isolation_with_o2','icu_no_o2','icu_with_o2','icu_ventilator','deaths_probable','tests_sent','lab_confirmed','positives_home','positives_inst','positives_isolation','positives_icu_no_o2','positives_icu_with_o2','positives_icu_ventilator'] as $field)
                                <td class="px-2 py-2.5 text-center font-black font-mono text-xs col-total" data-field="{{ $field }}" style="color:#006B4F;border-right:1px solid #D1EDE4;">0</td>
                            @endforeach
                            <td class="px-2 py-2.5 text-center font-black font-mono text-xs" id="totalPositivesSum" style="color:#DC2626;">0</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Footer actions --}}
            <div class="px-6 py-4 flex items-center justify-between" style="background:#F9FAFB;border-top:1px solid #E2EDE9;">
                <p class="text-xs" style="color:#6B7280;">
                    <span class="font-bold" style="color:#006B4F;">{{ $institutions->count() }}</span> institutions listed &bull; Blank rows are automatically skipped on save.
                </p>
                <div class="flex gap-3">
                    <a href="{{ route('ebola.index') }}" class="px-4 py-2 rounded-lg text-xs font-bold transition" style="background:#F3F4F6;color:#374151;border:1px solid #E5E7EB;">Cancel</a>
                    <button type="submit" class="px-5 py-2 rounded-lg text-xs font-bold text-white transition hover:opacity-90" style="background:#006B4F;">Save All</button>
                </div>
            </div>
        </div>
    </form>
</div>
</div>

<script>
    const fields = [
        'total_cases_reported','home_quarantine','inst_quarantine',
        'isolation_no_o2','isolation_with_o2','icu_no_o2','icu_with_o2','icu_ventilator',
        'deaths_probable','tests_sent','lab_confirmed',
        'positives_home','positives_inst','positives_isolation',
        'positives_icu_no_o2','positives_icu_with_o2','positives_icu_ventilator'
    ];

    const positiveFields = [
        'positives_home','positives_inst','positives_isolation',
        'positives_icu_no_o2','positives_icu_with_o2','positives_icu_ventilator'
    ];

    function recalcTotals() {
        const rows = document.querySelectorAll('.report-row');

        // Column totals
        const colSums = {};
        fields.forEach(f => colSums[f] = 0);

        rows.forEach((row, i) => {
            let rowPosTotal = 0;
            positiveFields.forEach(f => {
                const inp = row.querySelector(`input[name="rows[${i}][${f}]"]`);
                if (inp) rowPosTotal += parseInt(inp.value) || 0;
            });

            // Update total positives cell for this row
            const posCell = row.querySelector('.total-pos');
            if (posCell) posCell.textContent = rowPosTotal;

            fields.forEach(f => {
                const inp = row.querySelector(`input[name="rows[${i}][${f}]"]`);
                if (inp) colSums[f] += parseInt(inp.value) || 0;
            });
        });

        // Update footer totals
        document.querySelectorAll('.col-total').forEach(cell => {
            const field = cell.dataset.field;
            cell.textContent = colSums[field] || 0;
        });

        // Grand total positives
        const posTotalFields = ['positives_home','positives_inst','positives_isolation','positives_icu_no_o2','positives_icu_with_o2','positives_icu_ventilator'];
        const grandPos = posTotalFields.reduce((s, f) => s + (colSums[f] || 0), 0);
        document.getElementById('totalPositivesSum').textContent = grandPos;
    }

    // Attach listeners to all inputs
    document.querySelectorAll('#bulletinTable input[type="number"]').forEach(inp => {
        inp.addEventListener('input', recalcTotals);
    });

    // Initial calc on load
    recalcTotals();
</script>
@endsection
