@extends('layouts.app')

@section('content')
<div class="bg-[#F0F7F4] text-slate-900 min-h-screen font-sans antialiased relative overflow-hidden" 
     x-data="simulationApp({{ $baselineActiveCases }}, {{ $totalBeds }}, {{ $totalIcu }}, {{ $totalOxygenBeds }}, {{ $totalOxygenStorage }}, {{ $totalPotentialBeds }}, {{ $localbodies->toJson() }})">
    
    <!-- Clean Grid Background -->
    <div class="absolute inset-0 bg-[linear-gradient(to_right,#e2e8f0_1px,transparent_1px),linear-gradient(to_bottom,#e2e8f0_1px,transparent_1px)] bg-[size:3.5rem_3.5rem] opacity-40"></div>
    <div class="absolute top-0 left-1/4 w-[600px] h-[600px] bg-emerald-600/5 rounded-full blur-3xl -translate-y-1/2 pointer-events-none"></div>
    <div class="absolute bottom-0 right-1/4 w-[600px] h-[600px] bg-sky-600/5 rounded-full blur-3xl translate-y-1/2 pointer-events-none"></div>

    <!-- Top Navigation Command Header -->
    <header class="relative bg-white/90 backdrop-blur-md border-b border-slate-200 px-6 py-4 shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
            <div class="flex items-center space-x-4">
                <div class="relative flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75" :class="matrixBadgeClass"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3" :class="matrixBadgeDotClass"></span>
                </div>
                <div>
                    <div class="flex items-center space-x-2.5">
                        <span class="text-[10px] font-black uppercase tracking-widest bg-emerald-50 text-emerald-700 border border-emerald-200 px-2 py-0.5 rounded">COMMAND NETWORK</span>
                        <span class="text-[10px] font-black uppercase tracking-widest bg-slate-100 text-slate-600 border border-slate-200 px-2 py-0.5 rounded">GOK ALAPPUZHA</span>
                    </div>
                    <h1 class="text-lg font-black tracking-tight text-slate-900 mt-1">
                        PANDEMIC SURGE SIMULATOR & EMERGENCY DESK
                    </h1>
                </div>
            </div>
            
            <div class="flex items-center space-x-3">
                <a href="{{ route('plans.index') }}" class="px-4 py-2 text-xs font-bold rounded-lg border border-slate-200 text-slate-600 hover:text-slate-900 hover:bg-slate-50 transition duration-150 cursor-pointer">
                    Digital Plan
                </a>
                <a href="{{ route('search.index') }}" class="px-4 py-2 text-xs font-bold rounded-lg bg-[#006B4F] hover:bg-[#00875F] text-white shadow-lg shadow-emerald-900/20 hover:shadow-emerald-900/30 transition duration-150 cursor-pointer">
                    Global Plan Search
                </a>
            </div>
        </div>
    </header>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 z-10">
        
        <!-- Main Console Layout Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- Left Console Sidebar (Controls & Map - 5 Cols) -->
            <div class="lg:col-span-5 space-y-8">
                
                <!-- Operator Override Console Panel -->
                <div class="bg-white/90 backdrop-blur-md border border-slate-200 rounded-2xl p-6 shadow-sm relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-emerald-500/10 rounded-full blur-2xl"></div>
                    
                    <div class="flex justify-between items-center mb-6">
                        <span class="text-[10px] font-black tracking-widest text-slate-500 uppercase">Model Parameters</span>
                        <span class="text-xs text-emerald-600 font-bold font-mono">Telemetry Override</span>
                    </div>
                    
                    <div class="flex justify-between items-baseline mb-4">
                        <span class="text-slate-600 text-sm font-semibold">Outbreak Surge Scale:</span>
                        <span class="text-5xl font-black text-emerald-600 tracking-tighter font-mono" x-text="multiplier.toFixed(2) + 'x'">1.00x</span>
                    </div>

                    <!-- Modern Slider Interface -->
                    <div class="my-6">
                        <input type="range" min="0.25" max="4.00" step="0.25" x-model.number="multiplier"
                               class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-emerald-600 focus:outline-none border border-slate-300 shadow-inner">
                        <div class="flex justify-between text-[9px] text-slate-500 font-extrabold px-1 mt-2.5 font-mono">
                            <span>0.25x MILD</span>
                            <span>1.00x BASE</span>
                            <span>2.00x ELEVATED</span>
                            <span>3.00x SEVERE</span>
                            <span>4.00x CRITICAL</span>
                        </div>
                    </div>

                    <!-- Preset Selector Dials -->
                    <div class="grid grid-cols-5 gap-2 mt-4 font-mono">
                        <template x-for="val in [0.5, 1.0, 2.0, 3.0, 4.0]" :key="val">
                            <button @click="multiplier = val" 
                                    class="py-1.5 px-1 rounded text-[10px] font-black border transition duration-150 cursor-pointer"
                                    :class="multiplier === val ? 'bg-[#006B4F] border-emerald-600 text-white shadow-md' : 'border-slate-200 bg-slate-50 text-slate-600 hover:bg-slate-100 hover:text-slate-900'"
                                    x-text="val.toFixed(2) + 'x'">
                            </button>
                        </template>
                    </div>
                </div>

                <!-- Preparedness Category Matrix Indicator -->
                <div class="bg-white/90 backdrop-blur-md border rounded-2xl p-6 shadow-sm transition-all duration-300"
                     :class="matrixBorderClass">
                    
                    <div class="flex justify-between items-center mb-5">
                        <span class="text-[10px] font-black tracking-widest text-slate-500 uppercase">Trigger Classification</span>
                        <span class="inline-flex items-center px-3 py-1 rounded text-xs font-black tracking-widest border font-mono shadow-sm"
                              :class="matrixBadgeClass" x-text="'CAT ' + matrixCategory">
                            CAT A
                        </span>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <span class="text-[9px] text-slate-500 font-bold uppercase tracking-wider block">Surge Condition Threshold</span>
                            <p class="text-sm font-bold text-slate-800 mt-1" x-text="matrixTrigger"></p>
                        </div>

                        <div>
                            <span class="text-[9px] text-slate-500 font-bold uppercase tracking-wider block">Active Emergency Protocols</span>
                            <ul class="text-xs text-slate-600 mt-2.5 space-y-2 pl-4 list-disc font-sans leading-relaxed">
                                <template x-for="protocol in matrixProtocols" :key="protocol">
                                    <li class="hover:text-slate-900 transition duration-150" x-text="protocol"></li>
                                </template>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- GIS Telemetry Grid Map -->
                <div class="bg-white/90 backdrop-blur-md border border-slate-200 rounded-2xl p-6 shadow-sm">
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-[10px] font-black tracking-widest text-slate-500 uppercase">GIS Wards Telemetry</span>
                        <span class="text-[9px] font-black text-[#006B4F] font-mono tracking-widest">MUTHUKULAM BLOCK</span>
                    </div>

                    <!-- GIS grid board mockup representing Panchayats -->
                    <div class="relative w-full aspect-[4/3] flex items-center justify-center bg-slate-50 rounded-xl overflow-hidden border border-slate-200 p-4 shadow-inner">
                        <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,#f8fafc,transparent_70%)] opacity-80"></div>
                        <div class="absolute inset-0 grid grid-cols-3 grid-rows-3 gap-2 p-4 text-[10px] font-black text-slate-800 relative z-10 font-mono">
                            
                            <!-- Arattupuzha -->
                            <div @mouseenter="hoveredGp = 'Arattupuzha'" @mouseleave="hoveredGp = null"
                                 class="row-span-3 rounded-lg border flex flex-col items-center justify-center transition-all duration-300 cursor-pointer shadow-sm relative overflow-hidden group"
                                 :class="getGpBgClass('Arattupuzha')">
                                <div class="absolute inset-0 bg-gradient-to-t from-white/60 to-transparent"></div>
                                <span class="uppercase tracking-wider relative z-10 text-center">Arattupuzha</span>
                                <span class="text-[8px] opacity-80 mt-0.5 relative z-10" x-text="getGpCases('Arattupuzha') + ' cases'"></span>
                            </div>

                            <!-- Cheppad -->
                            <div @mouseenter="hoveredGp = 'Cheppad'" @mouseleave="hoveredGp = null"
                                 class="rounded-lg border flex flex-col items-center justify-center transition-all duration-300 cursor-pointer shadow-sm relative overflow-hidden group"
                                 :class="getGpBgClass('Cheppad')">
                                <div class="absolute inset-0 bg-gradient-to-t from-white/60 to-transparent"></div>
                                <span class="uppercase tracking-wider relative z-10 text-center">Cheppad</span>
                                <span class="text-[8px] opacity-80 mt-0.5 relative z-10" x-text="getGpCases('Cheppad') + ' cases'"></span>
                            </div>

                            <!-- Pathiyoor -->
                            <div @mouseenter="hoveredGp = 'Pathiyoor'" @mouseleave="hoveredGp = null"
                                 class="rounded-lg border flex flex-col items-center justify-center transition-all duration-300 cursor-pointer shadow-sm relative overflow-hidden group"
                                 :class="getGpBgClass('Pathiyoor')">
                                <div class="absolute inset-0 bg-gradient-to-t from-white/60 to-transparent"></div>
                                <span class="uppercase tracking-wider relative z-10 text-center">Pathiyoor</span>
                                <span class="text-[8px] opacity-80 mt-0.5 relative z-10" x-text="getGpCases('Pathiyoor') + ' cases'"></span>
                            </div>

                            <!-- Muthukulam -->
                            <div @mouseenter="hoveredGp = 'Muthukulam'" @mouseleave="hoveredGp = null"
                                 class="rounded-lg border flex flex-col items-center justify-center transition-all duration-300 cursor-pointer shadow-sm relative overflow-hidden group"
                                 :class="getGpBgClass('Muthukulam')">
                                <div class="absolute inset-0 bg-gradient-to-t from-white/60 to-transparent"></div>
                                <span class="uppercase tracking-wider relative z-10 text-center">Muthukulam</span>
                                <span class="text-[8px] opacity-80 mt-0.5 relative z-10" x-text="getGpCases('Muthukulam') + ' cases'"></span>
                            </div>

                            <!-- Devikulangara -->
                            <div @mouseenter="hoveredGp = 'Devikulangara'" @mouseleave="hoveredGp = null"
                                 class="rounded-lg border flex flex-col items-center justify-center transition-all duration-300 cursor-pointer shadow-sm relative overflow-hidden group"
                                 :class="getGpBgClass('Devikulangara')">
                                <div class="absolute inset-0 bg-gradient-to-t from-white/60 to-transparent"></div>
                                <span class="uppercase tracking-wider relative z-10 text-center">Devikulangara</span>
                                <span class="text-[8px] opacity-80 mt-0.5 relative z-10" x-text="getGpCases('Devikulangara') + ' cases'"></span>
                            </div>

                            <!-- Kandalloor -->
                            <div @mouseenter="hoveredGp = 'Kandalloor'" @mouseleave="hoveredGp = null"
                                 class="rounded-lg border flex flex-col items-center justify-center transition-all duration-300 cursor-pointer shadow-sm relative overflow-hidden group"
                                 :class="getGpBgClass('Kandalloor')">
                                <div class="absolute inset-0 bg-gradient-to-t from-white/60 to-transparent"></div>
                                <span class="uppercase tracking-wider relative z-10 text-center">Kandalloor</span>
                                <span class="text-[8px] opacity-80 mt-0.5 relative z-10" x-text="getGpCases('Kandalloor') + ' cases'"></span>
                            </div>

                            <!-- Krishnapuram -->
                            <div @mouseenter="hoveredGp = 'Krishnapuram'" @mouseleave="hoveredGp = null"
                                 class="rounded-lg border flex flex-col items-center justify-center transition-all duration-300 cursor-pointer shadow-sm relative overflow-hidden group"
                                 :class="getGpBgClass('Krishnapuram')">
                                <div class="absolute inset-0 bg-gradient-to-t from-white/60 to-transparent"></div>
                                <span class="uppercase tracking-wider relative z-10 text-center">Krishnapuram</span>
                                <span class="text-[8px] opacity-80 mt-0.5 relative z-10" x-text="getGpCases('Krishnapuram') + ' cases'"></span>
                            </div>

                        </div>
                    </div>

                    <!-- GIS telemetry details panel -->
                    <div class="mt-4 bg-slate-50 border border-slate-200 rounded-xl p-4 h-24 flex items-center justify-center shadow-inner">
                        <template x-if="hoveredGp">
                            <div class="w-full grid grid-cols-3 gap-2 text-center text-xs">
                                <div>
                                    <span class="text-[8px] text-slate-500 uppercase block font-mono font-black">Region</span>
                                    <span class="font-extrabold text-slate-800 uppercase mt-0.5 block" x-text="hoveredGp + ' GP'"></span>
                                </div>
                                <div>
                                    <span class="text-[8px] text-slate-500 uppercase block font-mono font-black">Projected Cases</span>
                                    <span class="font-black text-[#006B4F] mt-0.5 block font-mono" x-text="getGpCases(hoveredGp)"></span>
                                </div>
                                <div>
                                    <span class="text-[8px] text-slate-500 uppercase block font-mono font-black">Clinical Hazard</span>
                                    <span class="font-black text-rose-600 mt-0.5 block" x-text="getGpVulnerability(hoveredGp)"></span>
                                </div>
                            </div>
                        </template>
                        <template x-if="!hoveredGp">
                            <div class="text-center text-slate-500 text-xs italic font-sans flex items-center justify-center space-x-2">
                                <svg class="h-4 w-4 animate-pulse text-[#006B4F]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"/>
                                </svg>
                                <span>Inspect ward-level hazard projections by hovering regions on telemetry grid.</span>
                            </div>
                        </template>
                    </div>

                </div>

            </div>

            <!-- Right Console Mainboard (Projections & AI narratives - 7 Cols) -->
            <div class="lg:col-span-7 space-y-8">
                
                <!-- Circular Radial Gauge Indicators -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    
                    <!-- Circular Hospital Bed Demand Gauge -->
                    <div class="bg-white/90 backdrop-blur-md border border-slate-200 rounded-2xl p-6 shadow-sm hover:border-emerald-500/30 hover:shadow-lg transition-all duration-300">
                        <div class="flex items-center justify-between">
                            <div class="space-y-1.5">
                                <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest font-mono">Hospital Bed Demand</span>
                                <div class="flex items-baseline space-x-1.5">
                                    <span class="text-3xl font-black tracking-tight font-mono text-slate-900" x-text="bedDemand">0</span>
                                    <span class="text-xs text-slate-500">beds</span>
                                </div>
                                <span class="text-[10px] text-slate-500 block font-sans">
                                    Capacity: <span class="font-bold text-slate-700" x-text="currentBedCapacity">0</span> beds
                                </span>
                            </div>
                            
                            <!-- Circular SVG Gauge -->
                            <div class="relative flex items-center justify-center">
                                <svg class="w-20 h-20 transform -rotate-90">
                                    <circle cx="40" cy="40" r="32" stroke="currentColor" class="text-slate-100" stroke-width="6" fill="transparent" />
                                    <circle cx="40" cy="40" r="32" stroke="currentColor" stroke-width="6" fill="transparent"
                                            :stroke-dasharray="2 * Math.PI * 32"
                                            :stroke-dashoffset="(1 - Math.min(100, bedSaturationPct) / 100) * 2 * Math.PI * 32"
                                            :class="bedProgressClass + ' transition-all duration-500 ease-out'" />
                                </svg>
                                <span class="absolute font-mono text-xs font-black text-slate-700" x-text="bedSaturationPct + '%'">0%</span>
                            </div>
                        </div>
                        
                        <div class="mt-4 border-t border-slate-100 pt-3 flex justify-between items-center">
                            <span class="text-[9px] font-bold text-slate-500 uppercase">Logistics Status</span>
                            <span class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded tracking-wider border shadow-sm" :class="bedAlertClass" x-text="bedSaturationPct >= 100 ? 'BED DEFICIT!' : 'STABLE'">STABLE</span>
                        </div>
                    </div>

                    <!-- Circular ICU Bed Demand Gauge -->
                    <div class="bg-white/90 backdrop-blur-md border border-slate-200 rounded-2xl p-6 shadow-sm hover:border-emerald-500/30 hover:shadow-lg transition-all duration-300">
                        <div class="flex items-center justify-between">
                            <div class="space-y-1.5">
                                <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest font-mono">Critical ICU Occupancy</span>
                                <div class="flex items-baseline space-x-1.5">
                                    <span class="text-3xl font-black tracking-tight font-mono text-slate-900" x-text="icuDemand">0</span>
                                    <span class="text-xs text-slate-500">beds</span>
                                </div>
                                <span class="text-[10px] text-slate-500 block font-sans">
                                    Capacity: <span class="font-bold text-slate-700">{{ $totalIcu }}</span> beds
                                </span>
                            </div>
                            
                            <!-- Circular SVG Gauge -->
                            <div class="relative flex items-center justify-center">
                                <svg class="w-20 h-20 transform -rotate-90">
                                    <circle cx="40" cy="40" r="32" stroke="currentColor" class="text-slate-100" stroke-width="6" fill="transparent" />
                                    <circle cx="40" cy="40" r="32" stroke="currentColor" stroke-width="6" fill="transparent"
                                            :stroke-dasharray="2 * Math.PI * 32"
                                            :stroke-dashoffset="(1 - Math.min(100, icuSaturationPct) / 100) * 2 * Math.PI * 32"
                                            :class="icuProgressClass + ' transition-all duration-500 ease-out'" />
                                </svg>
                                <span class="absolute font-mono text-xs font-black text-slate-700" x-text="icuSaturationPct + '%'">0%</span>
                            </div>
                        </div>
                        
                        <div class="mt-4 border-t border-slate-100 pt-3 flex justify-between items-center">
                            <span class="text-[9px] font-bold text-slate-500 uppercase">Critical Care Status</span>
                            <span class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded tracking-wider border shadow-sm" :class="icuAlertClass" x-text="icuSaturationPct >= 100 ? 'CRITICAL SATURATION!' : 'STABLE'">STABLE</span>
                        </div>
                    </div>

                </div>

                <!-- Oxygen & Workforce Projections -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    
                    <!-- Oxygen reserves tracker -->
                    <div class="bg-white/90 backdrop-blur-md border border-slate-200 rounded-2xl p-5 shadow-sm">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest font-mono">Bulk Oxygen Reserve Runway</span>
                                <h4 class="text-xl font-bold tracking-tight text-slate-900 mt-1.5" x-text="oxygenDemand.toFixed(0) + ' Liters/min'"></h4>
                            </div>
                            <span class="text-[10px] font-extrabold uppercase font-mono px-2 py-0.5 rounded bg-sky-50 border border-sky-200 text-sky-700 shadow-sm" x-text="oxygenDepletionHours"></span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-2 mt-4 overflow-hidden border border-slate-200 shadow-inner">
                            <div class="h-full rounded-full transition-all duration-500 bg-sky-500" 
                                 :style="'width: ' + Math.max(0, Math.min(100, (100 - (multiplier * 22)))) + '%'"></div>
                        </div>
                        <p class="text-[9px] text-slate-500 font-bold mt-2 font-mono">RESERVES POOL: {{ number_format($totalOxygenStorage) }} L</p>
                    </div>

                    <!-- Workforce tracker -->
                    <div class="bg-white/90 backdrop-blur-md border border-slate-200 rounded-2xl p-5 shadow-sm">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest font-mono">Rostered Workforce demand</span>
                                <h4 class="text-xl font-bold tracking-tight text-slate-900 mt-1.5" x-text="workforceDemand + ' personnel'"></h4>
                            </div>
                            <span class="text-[10px] font-extrabold uppercase font-mono px-2 py-0.5 rounded bg-teal-50 border border-teal-200 text-teal-700 shadow-sm" x-text="workforceSaturation + '% pool'"></span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-2 mt-4 overflow-hidden border border-slate-200 shadow-inner">
                            <div class="h-full rounded-full transition-all duration-500 bg-teal-500" 
                                 :style="'width: ' + Math.min(100, workforceSaturation) + '%'"></div>
                        </div>
                        <p class="text-[9px] text-slate-500 font-bold mt-2 font-mono">STAFF POOL: 650 active staff</p>
                    </div>

                </div>

                <!-- Terminal Styled AISituational Diagnostic Narrative -->
                <div class="bg-white/90 backdrop-blur-md border border-slate-200 rounded-2xl p-6 shadow-sm relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-[3px] bg-gradient-to-r from-[#006B4F] via-emerald-400 to-sky-400"></div>
                    
                    <div class="flex justify-between items-center mb-4 mt-1">
                        <h3 class="text-xs font-black tracking-widest text-slate-600 uppercase flex items-center font-mono">
                            <span class="flex h-2 w-2 rounded-full bg-[#006B4F] animate-ping mr-2"></span>
                            AI Diagnostic Stream Logger
                        </h3>
                        <span class="text-[9px] text-slate-500 font-bold font-mono bg-slate-100 px-2 py-1 rounded">LIVE PREDICTIONS POOL</span>
                    </div>

                    <!-- Cyber narrative visual board (Light Version) -->
                    <div class="bg-slate-50 rounded-xl p-5 border border-slate-200 font-mono text-sm leading-relaxed text-slate-700 shadow-inner relative">
                        <div class="absolute top-3 right-3 text-[9px] font-bold text-emerald-700 bg-emerald-100 border border-emerald-200 px-1.5 py-0.5 rounded select-none animate-pulse shadow-sm">RUNNING</div>
                        <p class="font-mono text-slate-500 text-xs mb-3 border-b border-slate-200 pb-1.5">>>> SYSTEM DIAGNOSTIC RUN REPORT :</p>
                        <span x-text="getAiNarrative()" class="font-medium"></span>
                        <span class="inline-block w-1.5 h-4 bg-[#006B4F] animate-pulse align-middle ml-0.5"></span>
                    </div>
                </div>

                <!-- Emergency Auxiliary Infrastructure Planning conversions list -->
                <div class="bg-white/90 backdrop-blur-md border border-slate-200 rounded-2xl p-6 shadow-sm">
                    <div class="flex justify-between items-center mb-4 border-b border-slate-100 pb-4">
                        <div>
                            <h3 class="text-xs font-black tracking-widest text-slate-600 uppercase">Emergency Auxiliary Infrastructure</h3>
                            <p class="text-[10px] text-slate-500 mt-0.5 font-sans">Automatic buffer activation of community structures during clinical overflow.</p>
                        </div>
                        <div class="text-right">
                            <span class="text-3xl font-black text-[#006B4F] block font-mono" x-text="activeConversionsCount">0</span>
                            <span class="text-[8px] text-slate-500 font-bold uppercase tracking-wider block mt-0.5">Active CFLTCs</span>
                        </div>
                    </div>

                    <!-- Checklist Cards Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach ($conversions as $index => $c)
                            <div class="rounded-xl p-4 border transition-all duration-300 flex items-center justify-between shadow-sm relative overflow-hidden"
                                 :class="isInfraActive({{ $index }}) ? 'bg-emerald-50 border-emerald-200' : 'bg-slate-50 border-slate-200 opacity-70'">
                                <div class="flex items-center space-x-3 relative z-10">
                                    <span class="flex h-2 w-2 rounded-full shadow-sm" 
                                          :class="isInfraActive({{ $index }}) ? 'bg-[#006B4F] animate-pulse' : 'bg-slate-300'"></span>
                                    <div>
                                        <h4 class="text-xs font-bold tracking-tight" :class="isInfraActive({{ $index }}) ? 'text-emerald-900' : 'text-slate-700'" x-text="'{{ $c->name }}'"></h4>
                                        <p class="text-[9px] text-slate-500 font-bold uppercase mt-0.5" x-text="'{{ $c->localbody->name }} GP &bull; {{ $c->type }}'"></p>
                                    </div>
                                </div>
                                <div class="text-right relative z-10">
                                    <span class="text-[9px] font-black uppercase px-2 py-0.5 rounded font-mono shadow-sm"
                                          :class="isInfraActive({{ $index }}) ? 'bg-emerald-100 text-emerald-800 border-emerald-200' : 'bg-slate-100 text-slate-500 border-slate-200'"
                                          x-text="isInfraActive({{ $index }}) ? 'ACTIVE (+{{ $c->potential_beds }} beds)' : 'STANDBY'">
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>

        </div>

    </div>
</div>

<script>
    function simulationApp(baselineActiveCases, totalBeds, totalIcu, totalOxygenBeds, totalOxygenStorage, totalPotentialBeds, localbodiesJson) {
        return {
            multiplier: 1.00,
            baselineCases: baselineActiveCases,
            baseBedCapacity: totalBeds,
            baseIcuCapacity: totalIcu,
            baseOxygenBeds: totalOxygenBeds,
            baseOxygenStorage: totalOxygenStorage,
            basePotentialBeds: totalPotentialBeds,
            localbodies: localbodiesJson,
            hoveredGp: null,

            get activeCases() {
                return Math.round(this.baselineCases * this.multiplier);
            },

            // Hospital Bed Demand: 12% of active cases require hospital general beds
            get bedDemand() {
                return Math.round(this.activeCases * 0.12);
            },

            // ICU Occupancy: 4% of active cases require ICU care
            get icuDemand() {
                return Math.round(this.activeCases * 0.04);
            },

            // Oxygen Consumption Rate: 8% on active oxygen therapy, consuming 12 Liters/Min average each
            get oxygenDemand() {
                return (this.activeCases * 0.08) * 12;
            },

            // Staffing Requirements: 1 nurse/doctor per 5 active general beds, 1 per 2 ICU beds
            get workforceDemand() {
                return Math.round((this.bedDemand / 5) + (this.icuDemand / 2));
            },

            // Bed saturation calculations
            get bedSaturationPct() {
                return Math.round((this.bedDemand / this.currentBedCapacity) * 100);
            },

            get icuSaturationPct() {
                return Math.round((this.icuDemand / this.baseIcuCapacity) * 100);
            },

            get workforceSaturation() {
                return Math.round((this.workforceDemand / 650) * 100);
            },

            // Dynamic Capacity Expansion: Auto activate field hospitals during high surge Categories C & D
            get currentBedCapacity() {
                if (this.multiplier >= 3.0) {
                    return this.baseBedCapacity + this.basePotentialBeds;
                } else if (this.multiplier >= 2.0) {
                    // Activate 3 GPs: Muthukulam, Cheppad, Pathiyoor
                    const activePots = this.localbodies
                        .filter(l => ['Muthukulam', 'Cheppad', 'Pathiyoor'].includes(l.name))
                        .reduce((acc, curr) => acc + curr.potential_beds, 0);
                    return this.baseBedCapacity + activePots;
                }
                return this.baseBedCapacity;
            },

            get activeConversionsCount() {
                if (this.multiplier >= 3.0) {
                    return 21; // All 21 conversions (3 per localbody * 7 localbodies)
                } else if (this.multiplier >= 2.0) {
                    return 9; // 3 localbodies * 3 conversions each = 9 active
                }
                return 0;
            },

            isInfraActive(index) {
                if (this.multiplier >= 3.0) {
                    return true;
                } else if (this.multiplier >= 2.0) {
                    return index < 9;
                }
                return false;
            },

            // Dynamic warning calculations
            get oxygenDepletionHours() {
                const mins = this.baseOxygenStorage / this.oxygenDemand;
                const hours = Math.round(mins / 60);
                if (hours > 120) return ">120 hrs supply";
                if (hours <= 0 || !isFinite(hours)) return "0 hrs runway";
                return hours + " hrs remaining";
            },

            // Kerala matrix status classifications
            get matrixCategory() {
                if (this.multiplier >= 3.0) return 'D';
                if (this.multiplier >= 2.0) return 'C';
                if (this.multiplier >= 1.0) return 'B';
                return 'A';
            },

            get matrixBorderClass() {
                const cat = this.matrixCategory;
                if (cat === 'D') return 'border-rose-300 bg-rose-50';
                if (cat === 'C') return 'border-amber-300 bg-amber-50';
                if (cat === 'B') return 'border-sky-300 bg-sky-50';
                return 'border-slate-200 bg-white/90';
            },

            get matrixBadgeClass() {
                const cat = this.matrixCategory;
                if (cat === 'D') return 'bg-rose-100 text-rose-700 border-rose-200';
                if (cat === 'C') return 'bg-amber-100 text-amber-700 border-amber-200';
                if (cat === 'B') return 'bg-sky-100 text-sky-700 border-sky-200';
                return 'bg-emerald-100 text-emerald-700 border-emerald-200';
            },

            get matrixBadgeDotClass() {
                const cat = this.matrixCategory;
                if (cat === 'D') return 'bg-rose-500';
                if (cat === 'C') return 'bg-amber-500';
                if (cat === 'B') return 'bg-sky-500';
                return 'bg-emerald-500';
            },

            get matrixTrigger() {
                const cat = this.matrixCategory;
                if (cat === 'D') return 'CRITICAL HAZARD: Active case multiplier indicates severe critical care ICU overload (>3.00x).';
                if (cat === 'C') return 'HIGH SURGE ALERT: Significant hospital bed deficits projected across the district (>2.00x).';
                if (cat === 'B') return 'ELEVATED SURGE ALERT: Caseload showing active growth. Continuous buffer check activated (1.00x - 2.00x).';
                return 'NORMAL BASICAL STANDBY: Outbreak metrics within standard historical local limits (<1.00x).';
            },

            get matrixProtocols() {
                const cat = this.matrixCategory;
                if (cat === 'D') return [
                    'Declare maximum Category D Red Alert district-wide.',
                    'DECLARE immediate full conversion of all 21 community centers/schools into emergency CFLTC field care units.',
                    'Deploy emergency medical reserve forces and activate clinical volunteers.',
                    'Declare isolation zones in high-incidence coastal Grama Panchayats.',
                    'Divert bulk clinical oxygen storage supplies directly to tertiary wings.'
                ];
                if (cat === 'C') return [
                    'Activate Category C response protocols: begin care center conversions.',
                    'Deploy auxiliary beds at 9 conversions across Muthukulam, Cheppad, and Pathiyoor GPs (+330 Beds).',
                    'Transition TDMCH and GH Alappuzha to dedicated respiratory critical wings.',
                    'Deploy mobile diagnostics centers across localbodies.'
                ];
                if (cat === 'B') return [
                    'Execute routine block-level ward surveillance checks.',
                    'Set cylinder refilling stations and oxygen buffer stocks on 12-hour high alert.',
                    'Initiate regional patient load balancing to prevent hospital bottlenecks.',
                    'Provide daily updates to block medical administration.'
                ];
                return [
                    'Maintain daily surveillance, database updates, and digital plan maintenance.',
                    'Execute monthly checks on critical care supplies, drug arrays, and ASHA gear.',
                    'Organize virtual critical response training drills for primary clinical staff.',
                    'Coordinate block-level diagnostic facility reports.'
                ];
            },

            // Alerts styling
            get bedAlertClass() {
                const sat = this.bedSaturationPct;
                if (sat >= 100) return 'bg-rose-100 text-rose-700 border-rose-200';
                if (sat >= 75) return 'bg-amber-100 text-amber-700 border-amber-200';
                return 'bg-emerald-100 text-emerald-700 border-emerald-200';
            },

            get bedProgressClass() {
                const sat = this.bedSaturationPct;
                if (sat >= 100) return 'text-rose-500';
                if (sat >= 75) return 'text-amber-500';
                return 'text-emerald-500';
            },

            get icuAlertClass() {
                const sat = this.icuSaturationPct;
                if (sat >= 100) return 'bg-rose-100 text-rose-700 border-rose-200 animate-pulse';
                if (sat >= 75) return 'bg-amber-100 text-amber-700 border-amber-200';
                return 'bg-emerald-100 text-emerald-700 border-emerald-200';
            },

            get icuProgressClass() {
                const sat = this.icuSaturationPct;
                if (sat >= 100) return 'text-rose-500';
                if (sat >= 75) return 'text-amber-500';
                return 'text-emerald-500';
            },

            // Localized GIS styling helper (Light Mode Adaptations)
            getGpBgClass(name) {
                let baseFactor = 1.0;
                if (name === 'Arattupuzha') baseFactor = 1.4;
                if (name === 'Pathiyoor') baseFactor = 1.2;
                if (name === 'Krishnapuram') baseFactor = 1.1;
                
                const score = this.multiplier * baseFactor;
                if (score >= 3.2) return 'bg-rose-100 text-rose-800 border-rose-300';
                if (score >= 2.0) return 'bg-amber-100 text-amber-800 border-amber-300';
                return 'bg-white text-slate-700 border-slate-200 hover:border-emerald-300 hover:shadow-md';
            },

            getGpCases(name) {
                let baseCases = 100;
                if (name === 'Arattupuzha') baseCases = 170;
                if (name === 'Pathiyoor') baseCases = 130;
                if (name === 'Cheppad') baseCases = 110;
                if (name === 'Krishnapuram') baseCases = 120;
                if (name === 'Muthukulam') baseCases = 90;
                if (name === 'Kandalloor') baseCases = 80;
                if (name === 'Devikulangara') baseCases = 70;
                return Math.round(baseCases * this.multiplier);
            },

            getGpVulnerability(name) {
                if (name === 'Arattupuzha') return 'CRITICAL (Coastal Settlements Wards)';
                if (name === 'Pathiyoor') return 'HIGH (Clinical Vulnerability)';
                if (name === 'Krishnapuram') return 'MODERATE-HIGH (Transport Corridor)';
                return 'MODERATE';
            },

            // Live narrative assembler
            getAiNarrative() {
                const cases = this.activeCases;
                const beds = this.bedDemand;
                const icus = this.icuDemand;
                const mult = this.multiplier.toFixed(2);
                
                if (this.multiplier >= 3.0) {
                    return `CRITICAL ADVISORY // LEVEL ${mult}x // ACTIVE CASES projected at ${cases}. Bed saturation has hit ${this.bedSaturationPct}% general occupancy limits. Clinical beds at hospitals are completely depleted. In response, Category D Red Alert protocols have auto-activated all 21 planned community structures (schools & town halls), successfully routing ${this.basePotentialBeds} auxiliary beds to the active inventory. Warning: ICU occupancy is at ${this.icuSaturationPct}% of maximum baseline capacities, representing a severe deficit. Oxygen stores will deplete within ${this.oxygenDepletionHours} unless logistics vectors are rerouted. Direct emergency zoning is recommended in Arattupuzha GP coastal wards immediately.`;
                }
                
                if (this.multiplier >= 2.0) {
                    return `ELEVATED SITREP BRIEF // LEVEL ${mult}x // ACTIVE CASES projected at ${cases}. Bed saturation stands at ${this.bedSaturationPct}% of normal capacity. Category C triggers have converted 9 planned halls in Muthukulam GP, Cheppad GP, and Pathiyoor GP to dedicated Care Centers, increasing general bed index to ${this.currentBedCapacity}. Critical care ICU occupancy is at ${this.icuSaturationPct}%, and is highly active but stable. Projected bulk oxygen reserves runway: ${this.oxygenDepletionHours}. Balance referrals weekly.`;
                }

                if (this.multiplier >= 1.0) {
                    return `STANDARD OUTBREAK REPORT // LEVEL ${mult}x // ACTIVE CASES projected at ${cases}. General beds are occupied at ${this.bedSaturationPct}% of baseline. Critical care ICU stands stable at ${this.icuSaturationPct}%. Category B protocols are active: cylinder buffer systems are on 12-hour alert state-wide. No emergency conversions required. Project supply lines healthy with ${this.oxygenDepletionHours} of storage runway.`;
                }

                return `NORMAL STANDBY BRIEF // LEVEL ${mult}x // ACTIVE CASES projected at ${cases}. Outbreak metrics are completely within baseline limits. Bed occupancy (${beds}) and critical ICU care (${icus}) are fully covered. All 21 emergency structures remain on standby. Clinical centers are executing routine ward training and plan checks. System is highly stable.`;
            }
        };
    }
</script>
@endsection
