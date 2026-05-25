@extends('layouts.app')

@section('content')
<div class="min-h-screen" style="background:#F0F7F4;">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- ===== SUCCESS ALERT ===== --}}
        @if(session('success'))
            <div class="mb-6 flex items-center gap-3 px-4 py-3 rounded-xl border text-sm font-semibold"
                 style="background:#ECFDF5;border-color:#6EE7B7;color:#065F46;">
                <svg class="w-5 h-5 flex-shrink-0" style="color:#10B981;" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- ===== PAGE HEADER ===== --}}
        <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="w-2 h-2 rounded-full animate-pulse" style="background:#EF4444;"></span>
                    <span class="text-[10px] font-black uppercase tracking-widest font-mono px-2.5 py-1 rounded-full"
                          style="background:#FEF2F2;color:#DC2626;border:1px solid #FECACA;">
                        Ebola Outbreak Surveillance Console
                    </span>
                </div>
                <h1 class="text-2xl font-black tracking-tight" style="color:#111827;">Ebola Outbreak Surveillance Console</h1>
                <p class="text-sm mt-1" style="color:#6B7280;">Real-time bulletin compiled directly from individual patient surveillance records.</p>
            </div>
            <a href="{{ route('ebola.create') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold text-white shadow-lg transition hover:shadow-xl hover:opacity-90"
               style="background:linear-gradient(135deg,#006B4F,#00875F);box-shadow:0 4px 14px rgba(0,107,79,0.3);">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Log Patient Case
            </a>
        </div>

        {{-- ===== METRIC TABS ===== --}}
        <div class="mb-8" x-data="{ currentMode: 'daily' }">

            {{-- Tab bar --}}
            <div class="flex items-center justify-between mb-5 pb-4" style="border-bottom:1px solid #D1EDE4;">
                <h2 class="text-xs font-black uppercase tracking-widest font-mono" style="color:#6B7280;">Bulletin Aggregates</h2>
                <div class="flex gap-1 p-1 rounded-xl" style="background:#E8F5F0;border:1px solid #A7D4C3;">
                    <button @click="currentMode='daily'"
                            :class="currentMode==='daily' ? 'bg-white shadow-sm font-black' : 'font-semibold'"
                            class="px-4 py-1.5 rounded-lg text-xs transition-all"
                            :style="currentMode==='daily' ? 'color:#006B4F;' : 'color:#6B7280;'">
                        Daily Log
                    </button>
                    <button @click="currentMode='cumulative'"
                            :class="currentMode==='cumulative' ? 'bg-white shadow-sm font-black' : 'font-semibold'"
                            class="px-4 py-1.5 rounded-lg text-xs transition-all"
                            :style="currentMode==='cumulative' ? 'color:#006B4F;' : 'color:#6B7280;'">
                        Cumulative Totals
                    </button>
                </div>
            </div>

            {{-- DAILY METRICS --}}
            <div x-show="currentMode==='daily'" class="grid grid-cols-2 md:grid-cols-4 gap-4" x-transition>

                <div class="bg-white rounded-2xl p-5 border" style="border-color:#E2EDE9;box-shadow:0 1px 8px rgba(0,107,79,0.06);">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-[9px] font-black uppercase tracking-widest font-mono" style="color:#9CA3AF;">Daily Cases</span>
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background:#FEF2F2;">
                            <svg class="w-4 h-4" style="color:#DC2626;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                    </div>
                    <span class="text-3xl font-black font-mono block" style="color:#111827;">{{ $dailyCases }}</span>
                    <span class="text-[10px] mt-2 block font-mono" style="color:#9CA3AF;">Date: <span style="color:#374151;font-weight:700;">{{ $latestDate ?? 'N/A' }}</span></span>
                </div>

                <div class="bg-white rounded-2xl p-5 border" style="border-color:#E2EDE9;box-shadow:0 1px 8px rgba(0,107,79,0.06);">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-[9px] font-black uppercase tracking-widest font-mono" style="color:#9CA3AF;">Probable Admissions</span>
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background:#FFF7ED;">
                            <svg class="w-4 h-4" style="color:#D97706;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                    </div>
                    <span class="text-3xl font-black font-mono block" style="color:#111827;">{{ $dailyProbableAdmissions }}</span>
                    <span class="text-[10px] mt-2 block font-mono" style="color:#9CA3AF;">Iso: <span style="color:#374151;font-weight:700;">{{ $dailyIsolationNoO2 + $dailyIsolationWithO2 }}</span> &bull; ICU: <span style="color:#D97706;font-weight:700;">{{ $dailyIcuNoO2 + $dailyIcuWithO2 + $dailyIcuVentilator }}</span></span>
                </div>

                <div class="bg-white rounded-2xl p-5 border" style="border-color:#E2EDE9;box-shadow:0 1px 8px rgba(0,107,79,0.06);">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-[9px] font-black uppercase tracking-widest font-mono" style="color:#9CA3AF;">Tests Sent</span>
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background:#EFF6FF;">
                            <svg class="w-4 h-4" style="color:#2563EB;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        </div>
                    </div>
                    <span class="text-3xl font-black font-mono block" style="color:#111827;">{{ $dailyTestsSent }}</span>
                    <span class="text-[10px] mt-2 block font-mono" style="color:#9CA3AF;">Confirmed: <span style="color:#DC2626;font-weight:700;">{{ $dailyConfirmed }}</span></span>
                </div>

                <div class="bg-white rounded-2xl p-5 border" style="border-color:#E2EDE9;box-shadow:0 1px 8px rgba(0,107,79,0.06);">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-[9px] font-black uppercase tracking-widest font-mono" style="color:#9CA3AF;">Positives Admitted</span>
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background:#FFF1F2;">
                            <svg class="w-4 h-4" style="color:#E11D48;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                    </div>
                    <span class="text-3xl font-black font-mono block" style="color:#E11D48;">{{ $dailyPositivesAdmissions }}</span>
                    <span class="text-[10px] mt-2 block" style="color:#9CA3AF;">Active isolation &amp; critical care</span>
                </div>

            </div>

            {{-- CUMULATIVE METRICS --}}
            <div x-show="currentMode==='cumulative'" class="grid grid-cols-2 md:grid-cols-4 gap-4" x-transition>

                <div class="bg-white rounded-2xl p-5 border" style="border-color:#E2EDE9;box-shadow:0 1px 8px rgba(0,107,79,0.06);">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-[9px] font-black uppercase tracking-widest font-mono" style="color:#9CA3AF;">Total Cases</span>
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background:#F0FAF6;">
                            <svg class="w-4 h-4" style="color:#006B4F;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                    </div>
                    <span class="text-3xl font-black font-mono block" style="color:#111827;">{{ $cumulativeCases }}</span>
                    <span class="text-[10px] mt-2 block font-mono" style="color:#9CA3AF;">Home: <span style="color:#374151;font-weight:700;">{{ $cumulativeHomeQuarantine }}</span> &bull; Inst: <span style="color:#374151;font-weight:700;">{{ $cumulativeInstQuarantine }}</span></span>
                </div>

                <div class="bg-white rounded-2xl p-5 border" style="border-color:#E2EDE9;box-shadow:0 1px 8px rgba(0,107,79,0.06);">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-[9px] font-black uppercase tracking-widest font-mono" style="color:#9CA3AF;">Total Admissions</span>
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background:#FFF7ED;">
                            <svg class="w-4 h-4" style="color:#D97706;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                    </div>
                    <span class="text-3xl font-black font-mono block" style="color:#111827;">{{ $cumulativeProbableAdmissions }}</span>
                    <span class="text-[10px] mt-2 block font-mono" style="color:#9CA3AF;">ICU: <span style="color:#D97706;font-weight:700;">{{ $cumulativeIcuNoO2 + $cumulativeIcuWithO2 + $cumulativeIcuVentilator }}</span> &bull; Vent: <span style="color:#DC2626;font-weight:700;">{{ $cumulativeIcuVentilator }}</span></span>
                </div>

                <div class="bg-white rounded-2xl p-5 border" style="border-color:#E2EDE9;box-shadow:0 1px 8px rgba(0,107,79,0.06);">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-[9px] font-black uppercase tracking-widest font-mono" style="color:#9CA3AF;">Total Tests</span>
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background:#EFF6FF;">
                            <svg class="w-4 h-4" style="color:#2563EB;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        </div>
                    </div>
                    <span class="text-3xl font-black font-mono block" style="color:#111827;">{{ $cumulativeTestsSent }}</span>
                    <span class="text-[10px] mt-2 block font-mono" style="color:#9CA3AF;">Confirmed: <span style="color:#DC2626;font-weight:700;">{{ $cumulativeConfirmed }}</span></span>
                </div>

                <div class="bg-white rounded-2xl p-5 border" style="border-color:#E2EDE9;box-shadow:0 1px 8px rgba(0,107,79,0.06);">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-[9px] font-black uppercase tracking-widest font-mono" style="color:#9CA3AF;">Lab Positives</span>
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background:#FFF1F2;">
                            <svg class="w-4 h-4" style="color:#E11D48;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                    </div>
                    <span class="text-3xl font-black font-mono block" style="color:#E11D48;">{{ $cumulativePositivesAdmissions }}</span>
                    <span class="text-[10px] mt-2 block font-mono" style="color:#9CA3AF;">Quarantine: <span style="color:#374151;font-weight:700;">{{ $cumulativePositivesHome + $cumulativePositivesInst }}</span></span>
                </div>

            </div>
        </div>

        {{-- ===== CHART + QUARANTINE GRID ===== --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-8">

            {{-- Trend Chart --}}
            <div class="lg:col-span-8 bg-white rounded-2xl p-6 border" style="border-color:#E2EDE9;box-shadow:0 1px 8px rgba(0,107,79,0.06);">
                <div class="flex items-center justify-between mb-5">
                    <span class="text-[10px] font-black uppercase tracking-widest font-mono" style="color:#9CA3AF;">Historical Outbreak Trends</span>
                    <div class="flex gap-4 text-[9px] font-bold uppercase tracking-wider font-mono">
                        <span class="flex items-center gap-1.5" style="color:#006B4F;">
                            <span class="w-2.5 h-2.5 rounded block" style="background:#006B4F;"></span>Daily Cases
                        </span>
                        <span class="flex items-center gap-1.5" style="color:#0891B2;">
                            <span class="w-2.5 h-2.5 rounded block" style="background:#0891B2;"></span>Admissions
                        </span>
                    </div>
                </div>

                @if($chartData->isEmpty())
                    <div class="h-52 flex flex-col items-center justify-center rounded-xl" style="background:#F9FAFB;border:1px dashed #D1EDE4;">
                        <svg class="w-10 h-10 mb-3" style="color:#A7D4C3;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        <p class="text-sm font-semibold" style="color:#9CA3AF;">No data yet — log patient cases to generate analytics.</p>
                    </div>
                @else
                    <div class="h-52 flex items-end gap-2 px-2">
                        @foreach($chartData as $data)
                            @php
                                $maxVal = max($chartData->max('total_cases'), 1);
                                $heightPct = ($data->total_cases / $maxVal) * 100;
                                $admPct = ($data->total_admissions / $maxVal) * 100;
                            @endphp
                            <div class="flex-1 flex flex-col items-center group relative h-full justify-end">
                                <div class="absolute bottom-full mb-2 bg-white border rounded-lg p-2 text-[9px] opacity-0 group-hover:opacity-100 transition-opacity duration-150 pointer-events-none w-28 text-center shadow-lg font-mono z-50" style="border-color:#E2EDE9;color:#374151;">
                                    <p class="font-bold pb-0.5 mb-1" style="border-bottom:1px solid #E2EDE9;color:#111827;">{{ $data->date_of_reporting }}</p>
                                    <p style="color:#006B4F;">Cases: <span class="font-bold">{{ $data->total_cases }}</span></p>
                                    <p style="color:#0891B2;">Admitted: <span class="font-bold">{{ $data->total_admissions }}</span></p>
                                </div>
                                <div class="w-full flex items-end gap-0.5 h-full">
                                    <div class="w-1/2 rounded-t transition-all" style="height:{{ $heightPct }}%;background:#006B4F;opacity:0.8;"></div>
                                    <div class="w-1/2 rounded-t transition-all" style="height:{{ $admPct }}%;background:#0891B2;opacity:0.8;"></div>
                                </div>
                                <span class="text-[8px] mt-1.5 font-mono font-bold" style="color:#9CA3AF;">{{ date('d/m', strtotime($data->date_of_reporting)) }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Quarantine Distribution --}}
            <div class="lg:col-span-4 bg-white rounded-2xl p-6 border flex flex-col justify-between" style="border-color:#E2EDE9;box-shadow:0 1px 8px rgba(0,107,79,0.06);">
                <div>
                    <span class="text-[10px] font-black uppercase tracking-widest font-mono block mb-5" style="color:#9CA3AF;">Quarantine Distribution</span>
                    <div class="space-y-5">

                        <div>
                            <div class="flex justify-between text-xs font-bold mb-1.5">
                                <span style="color:#374151;">Home Quarantine</span>
                                <span class="font-mono" style="color:#006B4F;">{{ $cumulativeHomeQuarantine }}</span>
                            </div>
                            <div class="w-full rounded-full h-2 overflow-hidden" style="background:#E8F5F0;">
                                <div class="h-full rounded-full transition-all" style="width:{{ $cumulativeCases > 0 ? ($cumulativeHomeQuarantine / $cumulativeCases) * 100 : 0 }}%;background:#006B4F;"></div>
                            </div>
                        </div>

                        <div>
                            <div class="flex justify-between text-xs font-bold mb-1.5">
                                <span style="color:#374151;">Institutional Quarantine</span>
                                <span class="font-mono" style="color:#0891B2;">{{ $cumulativeInstQuarantine }}</span>
                            </div>
                            <div class="w-full rounded-full h-2 overflow-hidden" style="background:#E0F2FE;">
                                <div class="h-full rounded-full transition-all" style="width:{{ $cumulativeCases > 0 ? ($cumulativeInstQuarantine / $cumulativeCases) * 100 : 0 }}%;background:#0891B2;"></div>
                            </div>
                        </div>

                        <div>
                            <div class="flex justify-between text-xs font-bold mb-1.5">
                                <span style="color:#374151;">Critical ICU Care</span>
                                <span class="font-mono" style="color:#DC2626;">{{ $cumulativeIcuNoO2 + $cumulativeIcuWithO2 + $cumulativeIcuVentilator }}</span>
                            </div>
                            <div class="w-full rounded-full h-2 overflow-hidden" style="background:#FEE2E2;">
                                <div class="h-full rounded-full transition-all" style="width:{{ $cumulativeCases > 0 ? (($cumulativeIcuNoO2 + $cumulativeIcuWithO2 + $cumulativeIcuVentilator) / $cumulativeCases) * 100 : 0 }}%;background:#DC2626;"></div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="mt-6 pt-4 rounded-xl p-3" style="border-top:1px solid #E2EDE9;background:#F9FAFB;">
                    <p class="text-[9px] font-black uppercase tracking-wider font-mono mb-1" style="color:#9CA3AF;">Analysis Note</p>
                    <p class="text-[10px] leading-relaxed" style="color:#6B7280;">Higher isolation levels indicate increased resource allocation requirements across Medical College Hospitals.</p>
                </div>
            </div>

        </div>
        {{-- end grid --}}

        {{-- ===== DAILY SURVEILLANCE BULLETIN TABLE ===== --}}
        <div class="bg-white rounded-2xl overflow-hidden mb-8" style="border:1px solid #E2EDE9;box-shadow:0 1px 8px rgba(0,107,79,0.06);">

            <div class="px-6 py-4 flex flex-col md:flex-row md:items-center md:justify-between gap-4" style="border-bottom:1px solid #E2EDE9;background:linear-gradient(135deg,#F0FAF6,#E8F5F0);">
                <div>
                    <h2 class="text-base font-black" style="color:#006B4F;">Ebola Surveillance</h2>
                    <p class="text-xs mt-0.5" style="color:#6B7280;">Daily bulletin compiled from individual patient surveillance records.</p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <form action="{{ route('ebola.index') }}" method="GET" class="flex items-center gap-2">
                        <label class="text-[10px] font-black uppercase tracking-wider font-mono" style="color:#6B7280;">Date:</label>
                        <input type="date" name="bulletin_date" id="bulletin_date" value="{{ $bulletinDate }}" onchange="this.form.submit();"
                               class="px-3 py-1.5 rounded-lg text-xs font-bold cursor-pointer focus:outline-none"
                               style="border:1px solid #A7D4C3;background:#fff;color:#111827;font-family:monospace;">
                    </form>
                    <a href="{{ route('ebola.bulletin.download', ['bulletin_date' => $bulletinDate, 'type' => 'daily']) }}"
                       class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-xs font-bold text-white transition hover:opacity-90"
                       style="background:#006B4F;">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        Download CSV
                    </a>
                </div>
            </div>

            <div class="w-full overflow-x-auto">
                <table class="w-full border-collapse text-sm">
                    <thead>
                        <tr style="background:#F0FAF6;border-bottom:2px solid #D1EDE4;">
                            <th rowspan="2" class="px-4 py-3 text-left text-[9px] font-black uppercase tracking-widest font-mono whitespace-nowrap" style="color:#6B7280;border-right:1px solid #E2EDE9;">MCH / Hospital</th>
                            <th rowspan="2" class="px-4 py-3 text-center text-[9px] font-black uppercase tracking-widest font-mono whitespace-nowrap" style="color:#006B4F;border-right:1px solid #E2EDE9;background:#E8F5F0;">Cases Reported</th>
                            <th colspan="2" class="px-4 py-2 text-center text-[9px] font-black uppercase tracking-widest font-mono" style="color:#0891B2;border-bottom:1px solid #E2EDE9;border-right:1px solid #E2EDE9;background:#E0F2FE;">Quarantines</th>
                            <th colspan="5" class="px-4 py-2 text-center text-[9px] font-black uppercase tracking-widest font-mono" style="color:#D97706;border-bottom:1px solid #E2EDE9;background:#FFF7ED;">Admissions of Probable Cases</th>
                        </tr>
                        <tr style="background:#F9FAFB;border-bottom:1px solid #E2EDE9;">
                            <th class="px-3 py-2 text-center text-[9px] font-black uppercase tracking-widest font-mono whitespace-nowrap" style="color:#6B7280;border-right:1px solid #E2EDE9;">Home</th>
                            <th class="px-3 py-2 text-center text-[9px] font-black uppercase tracking-widest font-mono whitespace-nowrap" style="color:#6B7280;border-right:1px solid #E2EDE9;">Inst.</th>
                            <th class="px-3 py-2 text-center text-[9px] font-black uppercase tracking-widest font-mono whitespace-nowrap" style="color:#6B7280;border-right:1px solid #E2EDE9;">Iso (no O₂)</th>
                            <th class="px-3 py-2 text-center text-[9px] font-black uppercase tracking-widest font-mono whitespace-nowrap" style="color:#6B7280;border-right:1px solid #E2EDE9;">Iso (O₂)</th>
                            <th class="px-3 py-2 text-center text-[9px] font-black uppercase tracking-widest font-mono whitespace-nowrap" style="color:#6B7280;border-right:1px solid #E2EDE9;">ICU (no O₂)</th>
                            <th class="px-3 py-2 text-center text-[9px] font-black uppercase tracking-widest font-mono whitespace-nowrap" style="color:#6B7280;border-right:1px solid #E2EDE9;">ICU (O₂)</th>
                            <th class="px-3 py-2 text-center text-[9px] font-black uppercase tracking-widest font-mono whitespace-nowrap" style="color:#6B7280;">ICU (Vent)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bulletinRows as $row)
                            <tr class="transition-colors hover:bg-[#F0FAF6]" style="border-bottom:1px solid #F3F4F6;">
                                <td class="px-4 py-3 font-bold whitespace-nowrap" style="color:#111827;border-right:1px solid #E2EDE9;">
                                    {{ $row->institution_name ?? ($row->institution->name ?? '-') }}
                                </td>
                                <td class="px-4 py-3 text-center font-black font-mono whitespace-nowrap" style="color:#006B4F;border-right:1px solid #E2EDE9;background:#F0FAF6;">{{ $row->cases_reported }}</td>
                                <td class="px-3 py-3 text-center font-mono text-sm" style="color:#374151;border-right:1px solid #E2EDE9;">{{ $row->home_quarantine }}</td>
                                <td class="px-3 py-3 text-center font-mono text-sm" style="color:#374151;border-right:1px solid #E2EDE9;">{{ $row->inst_quarantine }}</td>
                                <td class="px-3 py-3 text-center font-mono text-sm" style="color:#0891B2;border-right:1px solid #E2EDE9;background:#F0F9FF;">{{ $row->isolation_no_o2 }}</td>
                                <td class="px-3 py-3 text-center font-mono text-sm" style="color:#0891B2;border-right:1px solid #E2EDE9;background:#F0F9FF;">{{ $row->isolation_with_o2 }}</td>
                                <td class="px-3 py-3 text-center font-mono text-sm" style="color:#D97706;border-right:1px solid #E2EDE9;background:#FFFBEB;">{{ $row->icu_no_o2 }}</td>
                                <td class="px-3 py-3 text-center font-mono text-sm" style="color:#D97706;border-right:1px solid #E2EDE9;background:#FFFBEB;">{{ $row->icu_with_o2 }}</td>
                                <td class="px-3 py-3 text-center font-mono text-sm font-bold" style="color:#DC2626;background:#FFF5F5;">{{ $row->icu_ventilator }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center gap-2">
                                        <svg class="w-10 h-10" style="color:#D1EDE4;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                        <p class="text-sm font-semibold" style="color:#9CA3AF;">No reporting data for this date.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ===== ACTIVE SURVEILLANCE CASE ROSTER ===== --}}
        <div class="bg-white rounded-2xl overflow-hidden mb-8" style="border:1px solid #E2EDE9;box-shadow:0 1px 8px rgba(0,107,79,0.06);">

            <div class="px-6 py-4 flex flex-col md:flex-row md:items-center md:justify-between gap-4" style="border-bottom:1px solid #E2EDE9;background:linear-gradient(135deg,#F0FAF6,#E8F5F0);">
                <div>
                    <h2 class="text-base font-black" style="color:#006B4F;">Active Surveillance Case Roster</h2>
                    <p class="text-xs mt-0.5" style="color:#6B7280;">Filter, search, or update individual case monitoring sheets.</p>
                </div>

                <form action="{{ route('ebola.index') }}" method="GET" class="flex flex-wrap gap-2 items-center">
                    <div class="relative">
                        <input type="text" name="search" placeholder="Search patient or MCH..." value="{{ request('search') }}"
                               class="pl-8 pr-3 py-2 rounded-lg text-xs font-semibold focus:outline-none w-48"
                               style="border:1px solid #A7D4C3;background:#fff;color:#111827;">
                        <svg class="absolute left-2.5 top-2.5 w-3.5 h-3.5" style="color:#9CA3AF;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>

                    <select name="status" onchange="this.form.submit()"
                            class="px-3 py-2 rounded-lg text-xs font-semibold focus:outline-none cursor-pointer"
                            style="border:1px solid #A7D4C3;background:#fff;color:#374151;">
                        <option value="">All Statuses</option>
                        <option value="Suspect" {{ request('status')==='Suspect' ? 'selected' : '' }}>Suspect</option>
                        <option value="Probable" {{ request('status')==='Probable' ? 'selected' : '' }}>Probable</option>
                        <option value="Confirmed" {{ request('status')==='Confirmed' ? 'selected' : '' }}>Confirmed</option>
                    </select>

                    <button type="submit" class="px-3 py-2 rounded-lg text-xs font-bold text-white transition hover:opacity-90" style="background:#006B4F;">Search</button>

                    @if(request()->filled('search') || request()->filled('status'))
                        <a href="{{ route('ebola.index') }}" class="px-3 py-2 rounded-lg text-xs font-bold transition"
                           style="background:#F3F4F6;color:#374151;border:1px solid #E5E7EB;">Reset</a>
                    @endif
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-sm">
                    <thead>
                        <tr style="background:#F9FAFB;border-bottom:2px solid #E2EDE9;">
                            <th class="px-5 py-3 text-left text-[9px] font-black uppercase tracking-widest font-mono" style="color:#6B7280;">Patient Name</th>
                            <th class="px-5 py-3 text-left text-[9px] font-black uppercase tracking-widest font-mono" style="color:#6B7280;">Age / Gender</th>
                            <th class="px-5 py-3 text-left text-[9px] font-black uppercase tracking-widest font-mono" style="color:#6B7280;">MCH / Facility</th>
                            <th class="px-5 py-3 text-center text-[9px] font-black uppercase tracking-widest font-mono" style="color:#6B7280;">Status</th>
                            <th class="px-5 py-3 text-left text-[9px] font-black uppercase tracking-widest font-mono" style="color:#6B7280;">Quarantine / Admission</th>
                            <th class="px-5 py-3 text-center text-[9px] font-black uppercase tracking-widest font-mono" style="color:#6B7280;">Test Status</th>
                            <th class="px-5 py-3 text-center text-[9px] font-black uppercase tracking-widest font-mono" style="color:#6B7280;">Outcome</th>
                            <th class="px-5 py-3 text-center text-[9px] font-black uppercase tracking-widest font-mono" style="color:#6B7280;">Date Logged</th>
                            <th class="px-5 py-3 text-right text-[9px] font-black uppercase tracking-widest font-mono" style="color:#6B7280;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cases as $case)
                            <tr class="transition-colors hover:bg-[#F0FAF6]" style="border-bottom:1px solid #F3F4F6;">
                                <td class="px-5 py-3 font-bold whitespace-nowrap" style="color:#111827;">{{ $case->patient_name }}</td>
                                <td class="px-5 py-3 whitespace-nowrap font-mono text-xs" style="color:#6B7280;">{{ $case->age }} yrs &bull; {{ $case->gender }}</td>
                                <td class="px-5 py-3 font-semibold whitespace-nowrap" style="color:#374151;">{{ $case->healthInstitution->name }}</td>
                                <td class="px-5 py-3 text-center whitespace-nowrap">
                                    @if($case->status === 'Confirmed')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold" style="background:#FEE2E2;color:#DC2626;">Confirmed</span>
                                    @elseif($case->status === 'Probable')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold" style="background:#FEF3C7;color:#D97706;">Probable</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold" style="background:#F3F4F6;color:#6B7280;">Suspect</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3 whitespace-nowrap">
                                    <div class="flex items-center gap-1.5">
                                        @if(str_contains($case->quarantine_type, 'ICU'))
                                            <span class="w-1.5 h-1.5 rounded-full animate-ping" style="background:#DC2626;"></span>
                                        @endif
                                        <span class="text-xs font-semibold" style="color:#374151;">{{ $case->quarantine_type }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-3 text-center whitespace-nowrap">
                                    @if($case->test_status === 'Positive')
                                        <span class="text-[10px] font-black font-mono" style="color:#DC2626;">POSITIVE</span>
                                    @elseif($case->test_status === 'Negative')
                                        <span class="text-[10px] font-black font-mono" style="color:#059669;">NEGATIVE</span>
                                    @elseif($case->test_status === 'Sent for Test')
                                        <span class="text-[10px] font-black font-mono" style="color:#2563EB;">SENT</span>
                                    @else
                                        <span class="text-[10px] font-mono" style="color:#9CA3AF;">UNTESTED</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3 text-center whitespace-nowrap">
                                    @if($case->outcome === 'Deceased')
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold" style="background:#FEE2E2;color:#DC2626;">Deceased</span>
                                    @elseif($case->outcome === 'Recovered')
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold" style="background:#D1FAE5;color:#059669;">Recovered</span>
                                    @else
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold" style="background:#DBEAFE;color:#2563EB;">Active</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3 text-center font-mono text-xs whitespace-nowrap" style="color:#9CA3AF;">{{ $case->date_of_reporting }}</td>
                                <td class="px-5 py-3 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('ebola.edit', $case->id) }}"
                                           class="text-[10px] font-bold px-2.5 py-1 rounded-lg transition hover:opacity-80"
                                           style="background:#E0F2FE;color:#0891B2;">Edit</a>
                                        <form action="{{ route('ebola.destroy', $case->id) }}" method="POST"
                                              onsubmit="return confirm('Delete this case file?');" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-[10px] font-bold px-2.5 py-1 rounded-lg transition hover:opacity-80 cursor-pointer"
                                                    style="background:#FEE2E2;color:#DC2626;">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-14 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <svg class="w-12 h-12" style="color:#D1EDE4;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                        <div>
                                            <p class="text-sm font-bold" style="color:#374151;">No case records found</p>
                                            <p class="text-xs mt-1" style="color:#9CA3AF;">Click "Log Patient Case" to register the first surveillance record.</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($cases->hasPages())
                <div class="px-6 py-4" style="border-top:1px solid #E2EDE9;background:#F9FAFB;">
                    {{ $cases->links() }}
                </div>
            @endif

        </div>

    </div>
</div>
@endsection
