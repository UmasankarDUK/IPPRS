@extends('layouts.app')

@section('content')
<div class="bg-white dark:bg-gray-900 min-h-screen flex flex-col md:flex-row">
    
    <!-- Left Sidebar: Table of Contents -->
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
                Pandemic Preparedness Blueprint
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

        <!-- Outline / Sections List -->
        <div class="flex-1 overflow-y-auto p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest">
                    Table of Contents
                </h3>
                <a href="{{ route('plans.sections.create', ['type' => $type, 'id' => $entity->id]) }}" class="inline-flex items-center p-1 border border-transparent rounded-full text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-950/30 focus:outline-none transition duration-150" title="Add Chapter">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </a>
            </div>
            
            <nav class="space-y-1.5" aria-label="Table of Contents">
                @forelse ($sections as $sec)
                    <a href="{{ route('plans.show', ['type' => $type, 'id' => $entity->id, 'sectionId' => $sec->id]) }}" 
                       class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-150 
                       @if($activeSection && $activeSection->id === $sec->id)
                           bg-indigo-600 text-white shadow-sm
                       @else
                           text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white
                       @endif">
                        <span class="truncate">{{ $sec->title }}</span>
                    </a>
                @empty
                    <p class="text-sm text-gray-400 dark:text-gray-500 italic p-2">No chapters created yet.</p>
                @endforelse
            </nav>
        </div>
        
    </div>

    <!-- Right Pane: Reading & Content Editor -->
    <div class="flex-1 bg-white dark:bg-gray-900 overflow-y-auto">
        
        <!-- Alerts Block -->
        @if(session('success'))
            <div class="bg-emerald-50 border-l-4 border-emerald-400 p-4 m-6 rounded shadow-sm">
                <div class="flex">
                    <div class="shrink-0">
                        <svg class="h-5 w-5 text-emerald-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-emerald-800 dark:text-emerald-200">
                            {{ session('success') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        @if($activeSection)
            <div class="max-w-4xl mx-auto px-6 py-12 lg:px-12">
                
                <!-- Action Controls -->
                <div class="flex justify-between items-center border-b border-gray-100 dark:border-gray-800 pb-6 mb-8">
                    <div>
                        <h2 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight leading-none">
                            {{ $activeSection->title }}
                        </h2>
                        <p class="text-xs text-gray-400 mt-2">
                            Last updated {{ $activeSection->updated_at->diffForHumans() }}
                        </p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('plans.sections.edit', ['sectionId' => $activeSection->id]) }}" class="inline-flex items-center px-3 py-1.5 border border-gray-200 dark:border-gray-700 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-750 focus:outline-none transition duration-150">
                            <svg class="h-4 w-4 mr-1 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit
                        </a>
                        <form action="{{ route('plans.sections.destroy', ['sectionId' => $activeSection->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this chapter? This cannot be undone.');" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-transparent rounded-lg text-sm font-medium text-red-700 bg-red-50 hover:bg-red-100 dark:bg-red-950/20 dark:text-red-400 dark:hover:bg-red-900/30 focus:outline-none transition duration-150 cursor-pointer">
                                <svg class="h-4 w-4 mr-1 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Delete
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Main Section Content (Rendered HTML) -->
                <div class="prose max-w-none dark:prose-invert text-gray-800 dark:text-gray-200">
                    {!! $activeSection->content !!}
                </div>

            </div>
        @else
            <div class="flex flex-col items-center justify-center h-full text-center p-12">
                <svg class="h-16 w-16 text-gray-300 dark:text-gray-700 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Document is Empty</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 max-w-sm">No chapters have been added yet to this pandemic preparedness plan. Start by creating the first chapter!</p>
                <div class="mt-6">
                    <a href="{{ route('plans.sections.create', ['type' => $type, 'id' => $entity->id]) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none transition">
                        Add First Chapter
                    </a>
                </div>
            </div>
        @endif

    </div>
    
</div>
@endsection
