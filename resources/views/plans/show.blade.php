@extends('layouts.app')

@section('content')
<div class="bg-white dark:bg-gray-900 min-h-screen flex flex-col md:flex-row">
    
    <!-- Left Sidebar: Modules Menu -->
    <div class="w-full md:w-80 bg-gray-50 dark:bg-gray-800/40 border-r border-gray-200 dark:border-gray-800 flex flex-col shrink-0">
        
        <!-- Sidebar Header (Entity Context) -->
        <div class="p-6 border-b border-gray-200 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/60">
            <div class="flex items-center space-x-2">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold uppercase tracking-wider
                    @if($type === 'district') bg-rose-100 text-rose-800 dark:bg-rose-900/30 dark:text-rose-300
                    @elseif($type === 'block') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300
                    @elseif($type === 'localbody') bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300
                    @else bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-300
                    @endif">
                    {{ $type }}
                </span>
            </div>
            <h1 class="mt-2 text-xl font-black text-gray-900 dark:text-white tracking-tight leading-snug">
                {{ $entity->name }} @if($type === 'localbody') GP @endif
            </h1>
            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                Pandemic Preparedness System
            </p>
            
            <div class="mt-4 flex space-x-2">
                <a href="{{ route('plans.index') }}" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline flex items-center font-medium">
                    <svg class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Archive
                </a>
            </div>
        </div>

        <!-- Modules List -->
        <div class="flex-1 overflow-y-auto p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest">
                    System Modules
                </h3>
            </div>
            
            <nav class="space-y-1.5" aria-label="System Modules">
                @foreach ($modules as $key => $title)
                    <a href="{{ route('plans.show', ['type' => $type, 'id' => $entity->id, 'sectionId' => $key]) }}" 
                       class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-150 mb-2
                       @if($activeModule === $key)
                           bg-indigo-600 text-white shadow-sm
                       @else
                           text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white
                       @endif">
                        <span class="truncate">{{ $title }}</span>
                    </a>
                @endforeach
            </nav>
        </div>
        
    </div>

    <!-- Right Pane: Data View -->
    <div class="flex-1 bg-white dark:bg-gray-900 overflow-y-auto p-6 lg:p-12">
        
        <div class="mb-10 border-b border-gray-100 dark:border-gray-800 pb-6">
            <h2 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight leading-none">
                {{ $modules[$activeModule] }}
            </h2>
        </div>
        
        @if($activeModule === 'overview')
            <!-- Nutshell Overview View -->
            <div class="max-w-5xl mx-auto">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-6 shadow-sm">
                        <div class="flex items-center space-x-2 mb-6">
                            <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest">Demographics Highlights</h3>
                        </div>
                        @if($type !== 'institution')
                            <div class="space-y-4">
                                <div class="flex justify-between items-center border-b border-gray-50 dark:border-gray-700/50 pb-2">
                                    <span class="text-gray-600 dark:text-gray-400 font-medium text-sm">Census Population</span>
                                    <span class="text-lg font-black text-gray-900 dark:text-white font-mono">{{ number_format($entity->population ?? 0) }}</span>
                                </div>
                                <div class="flex justify-between items-center pb-1">
                                    <span class="text-gray-600 dark:text-gray-400 font-medium text-sm">Total Area</span>
                                    <span class="text-lg font-black text-gray-900 dark:text-white font-mono">{{ number_format($entity->area_sq_km ?? 0, 2) }} <span class="text-xs text-gray-400 font-sans">sq.km</span></span>
                                </div>
                            </div>
                        @else
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 dark:text-gray-400 font-medium text-sm">Institution Type</span>
                                <span class="text-sm font-black text-gray-900 dark:text-white">{{ $entity->type }}</span>
                            </div>
                        @endif
                    </div>
                    
                    @if($type !== 'institution')
                    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-6 shadow-sm">
                        <div class="flex items-center space-x-2 mb-6">
                            <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest">Administrative Bodies</h3>
                        </div>
                        <div class="space-y-4">
                            @if(isset($overviewStats['total_blocks']))
                                <div class="flex justify-between items-center border-b border-gray-50 dark:border-gray-700/50 pb-2">
                                    <span class="text-gray-600 dark:text-gray-400 font-medium text-sm">Blocks</span>
                                    <span class="text-lg font-black text-gray-900 dark:text-white font-mono">{{ $overviewStats['total_blocks'] }}</span>
                                </div>
                            @endif
                            @if(isset($overviewStats['total_localbodies']))
                                <div class="flex justify-between items-center border-b border-gray-50 dark:border-gray-700/50 pb-2">
                                    <span class="text-gray-600 dark:text-gray-400 font-medium text-sm">Grama Panchayats</span>
                                    <span class="text-lg font-black text-gray-900 dark:text-white font-mono">{{ $overviewStats['total_localbodies'] }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between items-center pb-1">
                                <span class="text-gray-600 dark:text-gray-400 font-medium text-sm">Health Institutions</span>
                                <span class="text-lg font-black text-gray-900 dark:text-white font-mono">{{ $overviewStats['total_institutions'] }}</span>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Capacity Highlights -->
                <div class="bg-emerald-50 dark:bg-emerald-900/10 border border-emerald-100 dark:border-emerald-800/50 rounded-2xl p-8 shadow-sm">
                    <h3 class="text-xs font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-widest mb-6">
                        Clinical Resource Capacity
                    </h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
                        <div class="bg-white dark:bg-gray-800 p-4 rounded-xl border border-emerald-100 dark:border-emerald-800/30">
                            <span class="block text-3xl font-black text-emerald-700 dark:text-emerald-400 font-mono">{{ number_format($overviewStats['total_beds'] ?? 0) }}</span>
                            <span class="block text-[10px] font-black text-slate-500 uppercase mt-2 tracking-wider">General Beds</span>
                        </div>
                        <div class="bg-white dark:bg-gray-800 p-4 rounded-xl border border-emerald-100 dark:border-emerald-800/30">
                            <span class="block text-3xl font-black text-emerald-700 dark:text-emerald-400 font-mono">{{ number_format($overviewStats['total_icu'] ?? 0) }}</span>
                            <span class="block text-[10px] font-black text-slate-500 uppercase mt-2 tracking-wider">ICU Beds</span>
                        </div>
                        <div class="bg-white dark:bg-gray-800 p-4 rounded-xl border border-emerald-100 dark:border-emerald-800/30">
                            <span class="block text-3xl font-black text-emerald-700 dark:text-emerald-400 font-mono">{{ number_format($overviewStats['total_oxygen_beds'] ?? 0) }}</span>
                            <span class="block text-[10px] font-black text-slate-500 uppercase mt-2 tracking-wider">Oxygen Beds</span>
                        </div>
                        <div class="bg-white dark:bg-gray-800 p-4 rounded-xl border border-emerald-100 dark:border-emerald-800/30">
                            <span class="block text-3xl font-black text-emerald-700 dark:text-emerald-400 font-mono">{{ number_format($overviewStats['total_oxygen_storage'] ?? 0) }}<span class="text-sm">L</span></span>
                            <span class="block text-[10px] font-black text-slate-500 uppercase mt-2 tracking-wider">O2 Reserves</span>
                        </div>
                    </div>
                </div>
            </div>
            
        @elseif($activeModule === 'demographics')
            <div class="max-w-5xl mx-auto">
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900/50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Metric</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Value</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">Total Population (2011 Census)</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 font-mono">{{ number_format($entity->population ?? 0) }}</td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">Geographical Area</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 font-mono">{{ number_format($entity->area_sq_km ?? 0, 2) }} Sq.Km</td>
                            </tr>
                            @if(isset($entity->vulnerable_population))
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">Estimated Vulnerable Population</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 font-mono">{{ number_format($entity->vulnerable_population ?? 0) }}</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            
        @elseif($activeModule === 'subdivisions')
            <div class="max-w-5xl mx-auto">
                @if(isset($subdivisionsList) && $subdivisionsList->count() > 0)
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900/50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Code/Type</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Population</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($subdivisionsList as $sub)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 dark:text-white">{{ $sub->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $sub->code ?? $sub->type }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white text-right font-mono">{{ number_format($sub->total_population ?? $sub->population ?? 0) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <a href="{{ route('plans.show', ['type' => $type === 'district' ? 'block' : 'localbody', 'id' => $sub->id]) }}" class="text-indigo-600 hover:text-indigo-900">View Module</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                    <p class="text-gray-500">No subdivisions available for this entity.</p>
                @endif
            </div>

        @elseif($activeModule === 'healthcare')
            <div class="max-w-6xl mx-auto">
                @if(isset($healthcareList) && $healthcareList->count() > 0)
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-indigo-50 dark:bg-indigo-900/30">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-indigo-700 dark:text-indigo-300 uppercase">Institution Name</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-indigo-700 dark:text-indigo-300 uppercase">Location</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-indigo-700 dark:text-indigo-300 uppercase">Beds</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-indigo-700 dark:text-indigo-300 uppercase">ICU</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-indigo-700 dark:text-indigo-300 uppercase">O2 Beds</th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-indigo-700 dark:text-indigo-300 uppercase">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($healthcareList as $inst)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-gray-900 dark:text-white">{{ $inst->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $inst->type }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                    {{ $inst->localbody->name ?? 'Unknown GP' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white text-right font-mono">{{ $inst->capacity_beds }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white text-right font-mono">{{ $inst->capacity_icu }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white text-right font-mono">{{ $inst->capacity_oxygen_beds }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <a href="{{ route('plans.show', ['type' => 'institution', 'id' => $inst->id]) }}" class="text-indigo-600 hover:text-indigo-900">View Module</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                    <p class="text-gray-500">No healthcare infrastructure mapped for this entity.</p>
                @endif
            </div>
            
        @elseif($activeModule === 'alternative')
            <div class="max-w-6xl mx-auto">
                @if(isset($alternativeList) && $alternativeList->count() > 0)
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-emerald-50 dark:bg-emerald-900/30">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-emerald-700 dark:text-emerald-300 uppercase">Facility Name</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-emerald-700 dark:text-emerald-300 uppercase">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-emerald-700 dark:text-emerald-300 uppercase">Location</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-emerald-700 dark:text-emerald-300 uppercase">Est. Beds Capacity</th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-emerald-700 dark:text-emerald-300 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($alternativeList as $alt)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition">
                                <td class="px-6 py-4 text-sm font-bold text-gray-900 dark:text-white">{{ $alt->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">{{ $alt->type }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">{{ $alt->localbody->name ?? 'Unknown GP' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white text-right font-mono">{{ $alt->potential_beds }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-semibold">{{ $alt->status }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                    <p class="text-gray-500">No alternative infrastructure identified for conversion.</p>
                @endif
            </div>
        @endif

    </div>
    
</div>
@endsection
