@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen" x-data="{ filterType: 'all' }">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Back Link -->
        <div class="mb-6">
            <a href="{{ route('plans.index') }}" class="inline-flex items-center text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Digital Plan
            </a>
        </div>

        <!-- Headline -->
        <div class="text-center mb-10">
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight sm:text-4xl">
                Global Preparedness Search
            </h1>
            <p class="mt-2 text-md text-gray-500 dark:text-gray-400 max-w-2xl mx-auto">
                Instantly search across District, Block, Localbody, and Hospital plans for critical strategies, workforce rosters, or infrastructure protocols.
            </p>
        </div>

        <!-- Search Bar Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden mb-10">
            <div class="p-6">
                <form action="{{ route('search.index') }}" method="GET" class="flex gap-3">
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="text" name="q" value="{{ $query }}" required minlength="2" autofocus
                               class="block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-gray-650 rounded-xl text-gray-900 dark:text-white bg-white dark:bg-gray-750 placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 text-md shadow-inner" 
                               placeholder="Search terms (e.g. 'ASHA', 'Surveillance', 'Oxygen', 'CFLTC')...">
                    </div>
                    <button type="submit" class="px-6 py-3 border border-transparent text-sm font-semibold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out cursor-pointer">
                        Search Plans
                    </button>
                </form>
            </div>
        </div>

        @if(!empty($query))
            <!-- Results Metadata & Tabs -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between border-b border-gray-200 dark:border-gray-800 pb-4 mb-6">
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-3 sm:mb-0">
                    Found <span class="font-bold text-gray-900 dark:text-white">{{ $results->count() }}</span> matches for "<span class="font-semibold text-gray-900 dark:text-white">{{ $query }}</span>"
                </p>
                
                <!-- Alpine Filter Tabs -->
                <div class="flex space-x-1 bg-gray-100 dark:bg-gray-800/80 p-0.5 rounded-lg text-xs font-semibold">
                    <button @click="filterType = 'all'" :class="filterType === 'all' ? 'bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm' : 'text-gray-500 hover:text-gray-950 dark:text-gray-400 dark:hover:text-gray-250'" class="px-3 py-1.5 rounded-md transition cursor-pointer">All</button>
                    <button @click="filterType = 'district'" :class="filterType === 'district' ? 'bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm' : 'text-gray-500 hover:text-gray-950 dark:text-gray-400 dark:hover:text-gray-250'" class="px-3 py-1.5 rounded-md transition cursor-pointer">District</button>
                    <button @click="filterType = 'block'" :class="filterType === 'block' ? 'bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm' : 'text-gray-500 hover:text-gray-950 dark:text-gray-400 dark:hover:text-gray-250'" class="px-3 py-1.5 rounded-md transition cursor-pointer">Block</button>
                    <button @click="filterType = 'localbody'" :class="filterType === 'localbody' ? 'bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm' : 'text-gray-500 hover:text-gray-950 dark:text-gray-400 dark:hover:text-gray-250'" class="px-3 py-1.5 rounded-md transition cursor-pointer">Localbody</button>
                    <button @click="filterType = 'institution'" :class="filterType === 'institution' ? 'bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm' : 'text-gray-500 hover:text-gray-950 dark:text-gray-400 dark:hover:text-gray-250'" class="px-3 py-1.5 rounded-md transition cursor-pointer">Hospital</button>
                </div>
            </div>

            <!-- Results Stack -->
            <div class="space-y-6">
                @forelse($results as $res)
                    <div x-show="filterType === 'all' || filterType === '{{ $res['entity_type'] }}'" x-transition 
                         class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-md hover:border-gray-200 dark:hover:border-gray-650 transition duration-150">
                        
                        <!-- Hierarchy Breadcrumbs -->
                        <div class="flex items-center space-x-2 text-xs font-semibold uppercase tracking-wider mb-2">
                            <span class="px-2 py-0.5 rounded bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                {{ $res['entity_type'] }}
                            </span>
                            <span class="text-gray-300 dark:text-gray-600">/</span>
                            <span class="text-indigo-600 dark:text-indigo-400">
                                {{ $res['entity_name'] }}
                            </span>
                        </div>

                        <!-- Chapter Title -->
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2 leading-snug">
                            <a href="{{ route('plans.show', ['type' => $res['entity_type'], 'id' => $res['entity_id'], 'sectionId' => $res['id']]) }}" class="hover:text-indigo-600 dark:hover:text-indigo-400">
                                {!! $res['section_title'] !!}
                            </a>
                        </h3>

                        <!-- Snippet with matching terms -->
                        <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed font-sans mb-4">
                            {!! $res['snippet'] !!}
                        </p>

                        <!-- Actions Footer -->
                        <div class="flex justify-between items-center border-t border-gray-50 dark:border-gray-700/60 pt-4 mt-2">
                            <span class="text-xs text-gray-400">
                                Updated {{ $res['updated_at']->diffForHumans() }}
                            </span>
                            <a href="{{ route('plans.show', ['type' => $res['entity_type'], 'id' => $res['entity_id'], 'sectionId' => $res['id']]) }}" class="inline-flex items-center text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:underline">
                                Read Chapter
                                <svg class="h-3 w-3 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>

                    </div>
                @empty
                    <div class="text-center py-12 bg-white dark:bg-gray-800 rounded-2xl border border-gray-150 dark:border-gray-700">
                        <svg class="h-12 w-12 text-gray-300 dark:text-gray-700 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <h3 class="text-md font-bold text-gray-900 dark:text-white">No Results Found</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">We couldn't find any matches for "{{ $query }}". Make sure spelling is correct or try a different term.</p>
                    </div>
                @endforelse
            </div>
        @endif

    </div>
</div>
@endsection
