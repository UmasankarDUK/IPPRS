@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header Section -->
        <div class="mb-10 text-center md:text-left md:flex md:items-center md:justify-between">
            <div class="flex-1 min-w-0">
                <h2 class="text-3xl font-extrabold leading-7 text-gray-900 dark:text-white sm:text-4xl sm:truncate tracking-tight font-sans">
                    Pandemic Preparedness Digital Archive
                </h2>
                <p class="mt-2 text-md text-gray-500 dark:text-gray-400">
                     Timely updated digital repository converting static administrative plans to searchable, dynamic digital modules.
                </p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4 justify-center">
                <a href="{{ route('search.index') }}" class="inline-flex items-center px-4 py-2.5 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                    <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Global Plan Search
                </a>
            </div>
        </div>

        <!-- Tier Grid Dashboard -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
            
            <!-- District Card -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-md transition-shadow duration-300">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-800 dark:bg-rose-900/30 dark:text-rose-300">Tier 1</span>
                        <svg class="h-6 w-6 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">District Level</h3>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Broad strategies, inter-departmental coordination, and macro preparedness plans.</p>
                    <div class="mt-4 border-t border-gray-100 dark:border-gray-700 pt-4 flex justify-between items-center">
                        <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ $districts->count() }}</span>
                        <span class="text-xs text-gray-400">District Registered</span>
                    </div>
                </div>
            </div>

            <!-- Block Card -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-md transition-shadow duration-300">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">Tier 2</span>
                        <svg class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Block Level</h3>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Regional support networks, inter-panchayat logistics, and resource buffering.</p>
                    <div class="mt-4 border-t border-gray-100 dark:border-gray-700 pt-4 flex justify-between items-center">
                        <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ $blocks->count() }}</span>
                        <span class="text-xs text-gray-400">Blocks Registered</span>
                    </div>
                </div>
            </div>

            <!-- Localbody Card -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-md transition-shadow duration-300">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300">Tier 3</span>
                        <svg class="h-6 w-6 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Localbody (LSGI)</h3>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Grama Panchayats, community mobilization, field care facilities, and local surveillance.</p>
                    <div class="mt-4 border-t border-gray-100 dark:border-gray-700 pt-4 flex justify-between items-center">
                        <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ $localbodies->count() }}</span>
                        <span class="text-xs text-gray-400">LSGIs Registered</span>
                    </div>
                </div>
            </div>

            <!-- Institution Card -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-md transition-shadow duration-300">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-300">Tier 4</span>
                        <svg class="h-6 w-6 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Institution Level</h3>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Tertiary medical colleges, general hospitals, and primary health clinics.</p>
                    <div class="mt-4 border-t border-gray-100 dark:border-gray-700 pt-4 flex justify-between items-center">
                        <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ $institutions->count() }}</span>
                        <span class="text-xs text-gray-400">Facilities Seeding</span>
                    </div>
                </div>
            </div>
            
        </div>

        <!-- Tiered Navigation & Tables -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-2xl border border-gray-100 dark:border-gray-700 overflow-hidden" x-data="{ activeTab: 'districts' }">
            
            <!-- Tabs -->
            <div class="border-b border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50 px-6 py-4">
                <nav class="flex space-x-4" aria-label="Tabs">
                    <button @click="activeTab = 'districts'" :class="activeTab === 'districts' ? 'bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200'" class="px-4 py-2 font-medium text-sm rounded-lg transition duration-150 ease-in-out cursor-pointer">
                        Districts ({{ $districts->count() }})
                    </button>
                    <button @click="activeTab = 'blocks'" :class="activeTab === 'blocks' ? 'bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200'" class="px-4 py-2 font-medium text-sm rounded-lg transition duration-150 ease-in-out cursor-pointer">
                        Blocks ({{ $blocks->count() }})
                    </button>
                    <button @click="activeTab = 'localbodies'" :class="activeTab === 'localbodies' ? 'bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200'" class="px-4 py-2 font-medium text-sm rounded-lg transition duration-150 ease-in-out cursor-pointer">
                        Localbodies ({{ $localbodies->count() }})
                    </button>
                    <button @click="activeTab = 'institutions'" :class="activeTab === 'institutions' ? 'bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200'" class="px-4 py-2 font-medium text-sm rounded-lg transition duration-150 ease-in-out cursor-pointer">
                        Institutions ({{ $institutions->count() }})
                    </button>
                </nav>
            </div>

            <!-- Tab Contents -->
            <div class="p-6">
                
                <!-- Districts Tab -->
                <div x-show="activeTab === 'districts'" x-transition>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-900/50 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">District Name</th>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-900/50 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">State</th>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-900/50 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Census Population</th>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-900/50 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Area (sq. km)</th>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-900/50 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-150 dark:divide-gray-700">
                                @forelse ($districts as $district)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 dark:text-white">{{ $district->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $district->state }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ number_format($district->population) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $district->area_sq_km }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('plans.show', ['type' => 'district', 'id' => $district->id]) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 mr-4 font-semibold">View Plan</a>
                                        <a href="{{ route('dashboard') }}?district={{ $district->id }}" class="text-rose-600 dark:text-rose-400 hover:text-rose-900 dark:hover:text-rose-300 font-semibold">Simulator</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400 italic">No districts seeded yet. Run "php artisan db:seed" to parse documents.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Blocks Tab -->
                <div x-show="activeTab === 'blocks'" x-transition>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-900/50 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Block Name</th>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-900/50 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Parent District</th>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-900/50 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Est. Population</th>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-900/50 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Area (sq. km)</th>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-900/50 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-150 dark:divide-gray-700">
                                @forelse ($blocks as $block)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 dark:text-white">{{ $block->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $block->district->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ number_format($block->population) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $block->area_sq_km }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('plans.show', ['type' => 'block', 'id' => $block->id]) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 mr-4 font-semibold">View Plan</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400 italic">No block plans loaded.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Localbodies Tab -->
                <div x-show="activeTab === 'localbodies'" x-transition>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-900/50 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Localbody Name</th>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-900/50 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Parent Block</th>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-900/50 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Population</th>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-900/50 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Vulnerable Population</th>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-900/50 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-150 dark:divide-gray-700">
                                @forelse ($localbodies as $lb)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 dark:text-white">{{ $lb->name }} GP</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $lb->block->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ number_format($lb->population) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 dark:text-red-400 font-semibold">{{ number_format($lb->vulnerable_population) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('plans.show', ['type' => 'localbody', 'id' => $lb->id]) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 mr-4 font-semibold">View Plan</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400 italic">No localbody plans loaded.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Institutions Tab -->
                <div x-show="activeTab === 'institutions'" x-transition>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-900/50 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Institution Name</th>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-900/50 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Type</th>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-900/50 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Beds (ICU/Oxygen)</th>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-900/50 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">O2 Reserves (Liters)</th>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-900/50 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-150 dark:divide-gray-700">
                                @forelse ($institutions as $inst)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 dark:text-white">
                                        <div class="flex flex-col">
                                            <span>{{ $inst->name }}</span>
                                            <span class="text-xs text-gray-400">{{ $inst->localbody ? $inst->localbody->name . ' GP' : 'District Level' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300">
                                            {{ $inst->type }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 font-semibold">
                                        {{ $inst->capacity_beds }} total ({{ $inst->capacity_icu }} ICU / {{ $inst->capacity_oxygen_beds }} O2)
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 font-semibold">
                                        {{ number_format($inst->oxygen_storage_liters) }} L
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('plans.show', ['type' => 'institution', 'id' => $inst->id]) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 font-semibold">View Response Plan</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400 italic">No institutions loaded.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                
            </div>
            
        </div>
        
    </div>
</div>
@endsection
