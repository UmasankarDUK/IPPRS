@extends('layouts.app')

@section('content')
<div class="min-h-screen" style="background:#F0F7F4;">
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="mb-6">
        <a href="{{ route('ebola.index', ['bulletin_date' => $report->date_of_reporting->toDateString()]) }}"
           class="text-xs font-semibold hover:underline" style="color:#006B4F;">← Back to Dashboard</a>
        <h1 class="text-xl font-black mt-2" style="color:#111827;">Edit Daily Report</h1>
        <p class="text-sm mt-0.5" style="color:#6B7280;">
            {{ $report->healthInstitution->name }} &bull;
            {{ $report->date_of_reporting->format('d M Y') }}
        </p>
    </div>

    @if($errors->any())
        <div class="mb-4 px-4 py-3 rounded-xl border text-sm" style="background:#FEF2F2;border-color:#FECACA;color:#DC2626;">
            <ul class="list-disc list-inside">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST" action="{{ route('ebola.report.update', $report->id) }}">
        @csrf @method('PATCH')

        <div class="bg-white rounded-2xl p-6" style="border:1px solid #E2EDE9;box-shadow:0 2px 12px rgba(0,107,79,0.07);">

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-xs font-black uppercase tracking-wider font-mono mb-1" style="color:#6B7280;">Date of Reporting</label>
                    <input type="date" name="date_of_reporting" value="{{ $report->date_of_reporting->toDateString() }}"
                           class="w-full px-3 py-2 rounded-lg border text-sm font-semibold focus:outline-none"
                           style="border-color:#A7D4C3;color:#111827;">
                </div>
                <div>
                    <label class="block text-xs font-black uppercase tracking-wider font-mono mb-1" style="color:#6B7280;">MCH / Institution</label>
                    <select name="health_institution_id" class="w-full px-3 py-2 rounded-lg border text-sm font-semibold focus:outline-none" style="border-color:#A7D4C3;color:#111827;">
                        @foreach($institutions as $inst)
                            <option value="{{ $inst->id }}" {{ $report->health_institution_id == $inst->id ? 'selected' : '' }}>{{ $inst->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            @php
                $sections = [
                    'Cases' => [
                        'total_cases_reported' => 'Total Cases Reported',
                    ],
                    'Admissions of Probable Cases' => [
                        'home_quarantine'   => 'Home Quarantine (Suspect)',
                        'inst_quarantine'   => 'Institutional Quarantine (Probable)',
                        'isolation_no_o2'   => 'Isolation — No O₂',
                        'isolation_with_o2' => 'Isolation — With O₂',
                        'icu_no_o2'         => 'ICU — No O₂ / Ventilator',
                        'icu_with_o2'       => 'ICU — On Oxygen',
                        'icu_ventilator'    => 'ICU — On Ventilator',
                    ],
                    'Deaths' => [
                        'deaths_probable' => 'Deaths Among Probable Cases',
                    ],
                    'Testing' => [
                        'tests_sent'    => 'Cases Sent for Confirmatory Test',
                        'lab_confirmed' => 'Cases Confirmed by Lab Test',
                    ],
                    'Test Positives by Tier' => [
                        'positives_home'           => '+ve — Home Quarantine',
                        'positives_inst'           => '+ve — Institutional Quarantine',
                        'positives_isolation'      => '+ve — Isolation',
                        'positives_icu_no_o2'      => '+ve — ICU (no O₂)',
                        'positives_icu_with_o2'    => '+ve — ICU (O₂)',
                        'positives_icu_ventilator' => '+ve — ICU (Ventilator)',
                    ],
                ];
            @endphp

            @foreach($sections as $section => $fields)
                <div class="mb-5">
                    <h3 class="text-[9px] font-black uppercase tracking-widest font-mono mb-3 pb-1" style="color:#006B4F;border-bottom:1px solid #E2EDE9;">{{ $section }}</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        @foreach($fields as $field => $label)
                            <div>
                                <label class="block text-[10px] font-semibold mb-1" style="color:#6B7280;">{{ $label }}</label>
                                <input type="number" name="{{ $field }}" value="{{ old($field, $report->$field) }}" min="0"
                                       class="w-full px-3 py-2 rounded-lg border text-sm font-mono font-bold focus:outline-none text-center"
                                       style="border-color:#E2EDE9;color:#111827;">
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

            {{-- Computed read-only --}}
            <div class="mt-4 grid grid-cols-2 gap-4 p-4 rounded-xl" style="background:#F0FAF6;border:1px solid #D1EDE4;">
                <div class="text-center">
                    <p class="text-[9px] font-black uppercase tracking-wider font-mono" style="color:#9CA3AF;">Total Admissions (auto)</p>
                    <p class="text-2xl font-black font-mono mt-1" style="color:#006B4F;">{{ $report->total_admissions }}</p>
                </div>
                <div class="text-center">
                    <p class="text-[9px] font-black uppercase tracking-wider font-mono" style="color:#9CA3AF;">Total Positives (auto)</p>
                    <p class="text-2xl font-black font-mono mt-1" style="color:#DC2626;">{{ $report->total_positives }}</p>
                </div>
            </div>
        </div>

        <div class="mt-4 flex justify-end gap-3">
            <a href="{{ route('ebola.index', ['bulletin_date' => $report->date_of_reporting->toDateString()]) }}"
               class="px-4 py-2 rounded-lg text-sm font-bold" style="background:#F3F4F6;color:#374151;border:1px solid #E5E7EB;">Cancel</a>
            <button type="submit" class="px-5 py-2 rounded-lg text-sm font-bold text-white hover:opacity-90 transition" style="background:#006B4F;">Update Report</button>
        </div>
    </form>
</div>
</div>
@endsection
